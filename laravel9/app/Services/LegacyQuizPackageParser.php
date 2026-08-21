<?php
namespace App\Services;

class LegacyQuizPackageParser
{
    public function parse(string $file): array
    {
        if (!is_file($file)) return ['questions'=>[], 'status'=>'missing', 'message'=>'Файл пакета не найден'];
        $raw=file_get_contents($file);
        if ($raw===false) return ['questions'=>[], 'status'=>'error', 'message'=>'Не удалось прочитать файл'];

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
        foreach($m[0] as $candidate){
            $data=json_decode($candidate,true);
            if (!is_array($data)) continue;
            $q=$this->walkJson($data);
            if ($q) return $q;
        }
        return [];
    }

    private function walkJson(array $data): array
    {
        foreach($data as $k=>$v){
            if ($k==='questions' && is_array($v)) {
                $out=[];$pos=0;
                foreach($v as $row){
                    if(!is_array($row))continue;
                    $text=trim((string)($row['direction']??$row['question']??$row['text']??''));
                    if($text==='')continue;
                    $answers=$row['answers']??[];$opts=[];$correct=$answers['correctAnswerIndex']??$row['correctAnswerIndex']??null;
                    $correct=is_array($correct)?$correct:($correct===null?[]:[$correct]);
                    $list=$answers['answer']??$answers['items']??[];
                    foreach((array)$list as $i=>$a){if(is_array($a))$a=$a['text']??$a['value']??'';$opts[]=['text'=>(string)$a,'is_correct'=>in_array((int)$i,array_map('intval',$correct),true),'position'=>(int)$i];}
                    $out[]=['question'=>$text,'type'=>count($correct)>1?'multiple':'single','position'=>$pos++,'points'=>1,'options'=>$opts];
                }
                if($out)return $out;
            }
            if(is_array($v)){ $q=$this->walkJson($v); if($q)return $q; }
        }
        return [];
    }
}
