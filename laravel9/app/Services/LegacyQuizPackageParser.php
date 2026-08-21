<?php
namespace App\Services;

class LegacyQuizPackageParser
{
    public function parse(string $file): array
    {
        if (!is_file($file)) return ['questions'=>[], 'status'=>'missing', 'message'=>'Файл пакета не найден'];
        $raw=file_get_contents($file);
        if ($raw===false) return ['questions'=>[], 'status'=>'error', 'message'=>'Не удалось прочитать файл'];

        $ispring=$this->extractISpring($raw);
        if ($ispring) return ['questions'=>$ispring,'status'=>'parsed','message'=>'iSpring quizJson (base64+zlib)'];

        foreach ([$raw, html_entity_decode($raw, ENT_QUOTES|ENT_HTML5, 'UTF-8')] as $source) {
            $xml=$this->extractXml($source);
            if ($xml) {
                $q=$this->fromXml($xml);
                if ($q) return ['questions'=>$q,'status'=>'parsed','message'=>'iSpring/XML'];
            }
        }

        $json=$this->extractJson($raw);
        if ($json) return ['questions'=>$json,'status'=>'parsed','message'=>'JSON/JS'];

        return ['questions'=>[], 'status'=>'package_only', 'message'=>'Пакет сохранён, но структура вопросов автоматически не распознана'];
    }

    private function extractISpring(string $raw): array
    {
        if(!preg_match('/var\s+quizJson\s*=\s*["\']([^"\']+)["\']/s',$raw,$m))return [];
        $bin=base64_decode($m[1],true);if($bin===false)return [];
        $decoded=@gzuncompress($bin);
        if($decoded===false)$decoded=@gzinflate($bin);
        if($decoded===false && strlen($bin)>2)$decoded=@gzinflate(substr($bin,2));
        if($decoded===false)return [];
        $data=json_decode($decoded,true);
        if(!is_array($data))return [];
        return $this->walkJson($data);
    }

    private function extractXml(string $raw): ?string
    {
        if (preg_match('~<questions\b[^>]*>.*?</questions>~is',$raw,$m)) return '<?xml version="1.0" encoding="UTF-8"?><root>'.$m[0].'</root>';
        if (preg_match('~<quiz\b[^>]*>.*?</quiz>~is',$raw,$m)) return $m[0];
        return null;
    }

    private function fromXml(string $xmlText): array
    {
        libxml_use_internal_errors(true);
        $xml=simplexml_load_string($xmlText);
        if (!$xml) { libxml_clear_errors(); return []; }
        $nodes=$xml->xpath('//questions/*') ?: $xml->xpath('//question') ?: [];
        $out=[];$pos=0;
        foreach($nodes as $node){
            $text=trim((string)($node->direction ?? $node->text ?? $node->title ?? ''));
            if ($text==='') continue;
            $answersNode=$node->answers ?? null;$options=[];$correct=[];
            if ($answersNode) {
                $ci=(string)($answersNode['correctAnswerIndex'] ?? '');
                if ($ci!=='') foreach(preg_split('/[,;\s]+/',$ci,-1,PREG_SPLIT_NO_EMPTY) as $i)$correct[]=(int)$i;
                $i=0;foreach($answersNode->answer ?? [] as $a){$options[]=['text'=>trim((string)$a),'is_correct'=>in_array($i,$correct,true),'position'=>$i];$i++;}
            }
            if (!$options && $answersNode) { $i=0; foreach($answersNode->children() as $a){$options[]=['text'=>trim((string)$a),'is_correct'=>((string)($a['correct']??''))==='true','position'=>$i++];} }
            $out[]=['question'=>$text,'type'=>count($correct)>1?'multiple':'single','position'=>$pos++,'points'=>1,'options'=>$options];
        }
        libxml_clear_errors();return $out;
    }

    private function extractJson(string $raw): array
    {
        if (!preg_match_all('/\{[^{}]{0,50000}"questions"[^{}]{0,50000}\}/s',$raw,$m)) return [];
        foreach($m[0] as $candidate){$data=json_decode($candidate,true);if(!is_array($data))continue;$q=$this->walkJson($data);if($q)return $q;}
        return [];
    }

    private function walkJson(array $data): array
    {
        $direct=$this->questionFromNode($data,0);
        if($direct && $direct['options'])return [$direct];

        foreach($data as $k=>$v){
            if ($k==='questions' && is_array($v)) {
                $out=[];$pos=0;
                foreach($v as $row){if(!is_array($row))continue;$q=$this->questionFromNode($row,$pos);if($q){$out[]=$q;$pos++;}}
                if($out)return $out;
            }
        }

        $out=[];$pos=0;
        $this->collectQuestionNodes($data,$out,$pos);
        return $out;
    }

    private function collectQuestionNodes(array $data,array &$out,int &$pos): void
    {
        $q=$this->questionFromNode($data,$pos);
        if($q && $q['options']){$out[]=$q;$pos++;return;}
        foreach($data as $v)if(is_array($v))$this->collectQuestionNodes($v,$out,$pos);
    }

    private function questionFromNode(array $row,int $position): ?array
    {
        $answers=$row['answers']??$row['answerOptions']??$row['options']??null;
        if(!is_array($answers))return null;
        $text=$this->textValue($row['direction']??$row['question']??$row['questionText']??$row['title']??$row['text']??'');
        if($text==='')return null;

        $correct=$answers['correctAnswerIndex']??$row['correctAnswerIndex']??$row['correctAnswers']??$row['correctAnswer']??[];
        $correct=is_array($correct)?$correct:[$correct];$correct=array_map('intval',$correct);
        $list=$answers['answer']??$answers['items']??$answers['options']??$answers;
        $opts=[];$i=0;
        foreach((array)$list as $key=>$a){
            if(!is_int($key) && !ctype_digit((string)$key))continue;
            $isCorrect=false;$txt='';
            if(is_array($a)){$txt=$this->textValue($a['text']??$a['title']??$a['value']??$a['caption']??'');$isCorrect=(bool)($a['isCorrect']??$a['correct']??false);} else $txt=$this->textValue($a);
            if($txt==='')continue;
            if(in_array($i,$correct,true))$isCorrect=true;
            $opts[]=['text'=>$txt,'is_correct'=>$isCorrect,'position'=>$i++];
        }
        if(!$opts)return null;
        $correctCount=count(array_filter($opts,fn($o)=>$o['is_correct']));
        return ['question'=>$text,'type'=>$correctCount>1?'multiple':'single','position'=>$position,'points'=>1,'options'=>$opts];
    }

    private function textValue($v): string
    {
        if(is_string($v)||is_numeric($v))return trim(strip_tags((string)$v));
        if(!is_array($v))return '';
        foreach(['text','value','plainText','html','caption','title'] as $k)if(isset($v[$k])){$x=$this->textValue($v[$k]);if($x!=='')return $x;}
        return '';
    }
}
