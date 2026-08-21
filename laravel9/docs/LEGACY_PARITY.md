# Карта переноса legacy → Laravel 9

Старые файлы остаются нетронутыми до функционального сравнения и импорта данных.

| Старый контур / страницы | Новый модуль | Статус основы |
|---|---|---|
| `login.php`, `loginpr.php`, cookies/session | `AuthController`, Laravel Auth, роли | создано |
| `testy.php`, `nmo.php`, `media.php`, `tm_media*`, `tm_nmo_razd*` | Programs / LearningSection / ContentItem / Progress | создано |
| `test.php`, `test_nmo.php`, `res_test.php`, `tm_spec_test`, `tm_user_test` | Quiz / Questions / Options / Attempts | создано |
| `nmo_pract.php`, `nmo_prakt_*`, `pract_*`, `tm_user_pract` | PracticeAssignment / Submission | создано |
| `kont_rab.php`, `tm_irab_*` | Submission + типы промежуточной/итоговой работы | модель расширяется при импорте |
| `docs.php`, `get_dipl*.php`, `get_sert.php`, `tm_docs`, `tm_arh_diplom` | EducationDocument | создано |
| `user_forum.php`, `kurator_forum.php` | ForumTopic / ForumPost | создано |
| `tm_chat_kurator`, `pochta.php` | Messages | схема создана |
| `kurator.php`, `kurator_stat.php`, `kurator_prov*` | Curator dashboard / review / analytics | базовый кабинет создан |
| `adm_user.php`, `add_spec.php`, `administrator/*` | Admin CRUD | базовый кабинет создан |
| `tm_news`, `tm_user_obiav`, `kurator_obiav.php` | Announcements | схема и вывод созданы |
| архивы `tm_*_arh` | history/audit + legacy import | предусмотрено |

## Принцип совместимости данных

Новые сущности содержат `legacy_id`. Импорт не меняет старую БД и может запускаться повторно через `updateOrCreate`. Старые пароли не сохраняются открытым текстом: при импорте `tm_user.passw` сразу преобразуется Laravel `Hash::make()`.

## Следующие этапы переноса

1. Импорт структуры учебных разделов/материалов и их связей с программами.
2. Импорт банков вопросов, вариантов ответов и истории попыток.
3. Импорт практики, дневников, контрольных и итоговых работ, файлов.
4. Полный CRUD администратора и инструменты массового назначения групп/программ.
5. Проверка работ куратором, детальный прогресс, печатные формы и статистика.
6. Импорт документов, удостоверений/сертификатов, архива и отправки.
7. Форум, личные сообщения, объявления и отметки прочтения.
8. Сверка по каждой legacy-странице перед отключением старой системы.
