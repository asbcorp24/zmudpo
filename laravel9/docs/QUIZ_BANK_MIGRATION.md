# Перенос legacy-банка тестов

Legacy-система хранит карточки тестов в `tm_test`, а сами тесты — как HTML/iSpring-пакеты в `testy/`. PDF с ответами находятся в `otv/`.

## Порядок

```bash
php artisan migrate
php artisan legacy:import-quiz-bank --dry-run
php artisan legacy:import-quiz-bank
php artisan legacy:import-quiz-business --dry-run
php artisan legacy:import-quiz-business
php artisan legacy:import-nmo-quiz-state --dry-run
php artisan legacy:import-nmo-quiz-state
```

Если legacy-каталоги лежат в другом месте:

```bash
php artisan legacy:import-quiz-bank \
  --tests-dir=/path/to/testy \
  --answers-dir=/path/to/otv
```

## Что переносится

- `tm_test.num -> quizzes.legacy_id`;
- название, дата, `col_v`, исходные имена HTML/iSpring, изображения и PDF ответов;
- исходный пакет архивируется в `storage/app/legacy-quiz-bank/tests/{legacy_id}`;
- PDF ответов архивируется в `storage/app/legacy-quiz-bank/answers`;
- iSpring `quizJson` (`base64 + zlib`) распаковывается;
- распознанные вопросы -> `quiz_questions`;
- варианты и правильность -> `quiz_options`.

Импорт идемпотентный по карточкам (`legacy_id`). При первом успешном разборе вопросы создаются из legacy-пакета. При повторном запуске уже существующий нормализованный банк **не удаляется и не пересоздаётся**, чтобы не сломать ссылки в новых Laravel-попытках.

Для сознательного повторного разбора используется:

```bash
php artisan legacy:import-quiz-bank --force-reparse
```

Если при `--force-reparse` уже существуют новые Laravel-попытки, команда выводит предупреждение: сохранённые `answers` могут содержать старые ID вопросов/вариантов.

## Статусы

- `parsed` — вопросы и варианты нормализованы;
- `package_only` — исходный пакет сохранён, но автоматический парсер не распознал его структуру;
- `missing` — исходный файл отсутствует;
- `error` — ошибка чтения/разбора.

После импорта статусы и количество нормализованных вопросов видны в `/admin/quizzes`.

Для исторического `tm_user_test.res` процент рассчитывается по `quizzes.legacy_question_count` (`tm_test.col_v`), а не по количеству вопросов, которое смог распознать парсер.
