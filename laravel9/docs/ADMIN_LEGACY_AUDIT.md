# Полный аудит legacy-админки ZMUDPO

Аудит выполнен по верхнеуровневым `administrator/*.php`. Вложенные `phpqrcode/*`, `_mmServerScripts/*`, `_notes/*`, `Connections/*` классифицированы как сторонний/технический код и не являются самостоятельной бизнес-логикой. Банк тестовых вопросов пользователь переносит самостоятельно; поэтому файлы управления содержимым `tm_test` и XML-вопросами не импортируют вопросный контент, но их бизнес-окружение (назначение, доступ, попытки, результаты, отчёты) сохранено.

| Legacy PHP | Назначение | Основные таблицы/данные | Laravel 9 |
|---|---|---|---|
| index.php, add_admin.php | вход/администраторы | tm_admin | users(role=admin), Laravel Auth |
| add_menu.php, add_menu_adm.php, menu.php | права по пунктам меню | tm_menu, tm_menu_adm | admin_permissions |
| add_spec.php, add_spec_edit.php, add_typ_spec.php | программы и типы | tm_spec, tm_spec_type, tm_sert | programs, program_types, certificate_templates |
| add_spec_dop.php, add_spec_dop_soder.php | динамические поля программы | tm_spec_dop, tm_spec_zn | custom_field_definitions/values(scope=program) |
| add_spec_user.php, add_spec_user_.php | слушатели, массовое создание/перевод, юрлица, подгруппы, оплата, отправка | tm_user | users + enrollments + BulkUserController |
| add_tpsv.php, pechat_user.php | доп. сведения слушателя/печать | tm_typsv, tm_user_sv | custom fields(scope=user) + reports |
| printaddr.php | почтовый адрес | tm_addr_otprav | mailing_addresses |
| add_dat_user.php | статистика входов | tm_login_dat | login_events/report |
| add_doc.php, add_doc_p.php, add_docf.php, add_doc_spec.php | библиотека документов/URL и привязка программ | tm_docs, tm_doc_spec | resource_library + program_resource |
| add_media.php, add_media_spec.php, media.php | видео/аудио/книги | tm_media, tm_media_spec | resource_library |
| add_nmo.php, nmo.php | разделы НМО и 20+ типов контента | tm_nmo_razd, tm_nmo_razd_media, *_list, *_sert_test | learning_sections/content_items + settings/response_type |
| add_prepod.php, add_prepod_spec.php | преподаватели | tm_prepod, tm_prepod_spec | instructors + instructor_program |
| add_prepod_nmo.php, add_prepod_nmo_dat.php | НМО преподаватель/предмет/слоты | tm_nmo_prepod_spec, tm_nmo_prepod_dat | instructor_program + instructor_slots |
| show_user_nmo_dat.php | матрица посещений | tm_nmo_user_dat | instructor_slot_user/report |
| add_pract.php, add_pract_spec.php | циклы практики, задания, вложения, сроки | tm_pract, tm_pract_temy, tm_pract_temy_file, tm_spec_pract | practice_cycles/tasks/program pivot |
| add_itog_rab.php, add_itog_rab_sp.php | виды и темы промеж./итоговых работ | tm_irab_spec, tm_irab_tem, tm_irab_def_sp | final_work_definitions/themes/program |
| adm_itog_rap.php, upd_itog.php | проверка и оценка работ | tm_irab_stud | final_works + ReviewController |
| add_typsv_nmo.php, add_ank_sved.php | конструктор и отчёты анкет | tm_typsv_name, tm_typsv_konf, *_user | surveys/fields/responses |
| add_file_sved.php | отчёт файловых ответов | tm_konf_user_files, tm_nmo_razd_media | content/submission reports |
| add_array_nazv.php, add_array_grupp.php, add_array_string.php | универсальные справочники | tm_string_array_* | reference_catalogs/groups/values |
| add_tag.php | теги | tm_teg | reference catalog kind=tag |
| add_ball.php, ball.php | шкала процентов→оценка | ball.txt | app_settings grade_3/4/5 |
| add_sert.php | шаблоны сертификатов | tm_sert | certificate_templates |
| add_otziv.php | отзывы | tm_otziv | testimonials |
| adm_news.php | новости | tm_news | news |
| add_arh.php, xml_load.php | импорт архивного снимка программы/учащихся/оценок/доп. полей | tm_arh_spec, ts_arh_stud, tm_arh_ball, tm_arh_dop_sv | archive importer/snapshots |
| add_spec_test.php | назначение теста программе, попытки, активность | tm_spec_test | quizzes business assignment (без банка вопросов) |
| add_student_nmo_test_path.php, add_test_user_nmo_spec.php | персональная активация/ручная корректировка | *_user_act_test, tm_nmo_razd_user | quiz_user_overrides + audit_logs |
| red_d.php | ручное исправление результатов | tm_user_test | audited result correction |
| spec_otch.php, spec_otch_pech.php | успеваемость по программе/печать | tm_spec_test, tm_user_test | reports |
| gml.php | уведомление группы об открытии теста | email + tm_spec_test | Laravel notification/mail workflow |
| add_test.php, add_test_view.php | файловый каталог старых тестов | tm_test | **контент банка исключён по решению владельца** |
| parsttestnmo.php, parsttestnmores.php | чтение XML ответов старых тестов | userxml | **legacy viewer; вопросный контент не импортируется** |
| get_spec.php | простой endpoint списка программ | tm_spec | Program API/query |
| menu_nmo.php | статическое подменю | — | Laravel navigation |
| MhtFileMaker.php | сторонний MHT export helper | файлы | заменён HTML/печать/современный экспорт |
| phpqrcode/* | сторонняя QR-библиотека | — | не переносится как бизнес-код; использовать поддерживаемый пакет |

## Типы NMO content из add_nmo.php
1 document, 2 video, 3 test, 4 control work, 5 completion marker, 6 questionnaire, 7 file, 10 screenshot/text response, 11 payment, 12 link, 15 certificate test, 16 practice, 17 notebook template, 18 questionnaire test, 19 CSV/table, 20 random number, 21 test with answers, 22 exam.

Все типы должны храниться как `content_items.type` + `settings`, а специфические пользовательские результаты — в progress/submission/quiz/survey сущностях. Это сохраняет поведение без копирования небезопасного legacy SQL.
