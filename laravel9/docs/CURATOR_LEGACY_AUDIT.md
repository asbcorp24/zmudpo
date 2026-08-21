# Аудит legacy-кабинета куратора

Источник истины — корневые PHP-страницы `kurator*.php` и меню `kb.php`.

| Legacy | Назначение | Laravel |
|---|---|---|
| `kurator.php` | Успеваемость, последние входы, NMO-результаты и история | `/curator` + `/curator/students/{enrollment}` |
| `kurator_add.php` | Управление материалами/ссылками закреплённых разделов | `/curator/content` |
| `kurator_prov.php` | Проверка работ/изображений | `/curator/review` |
| `kurator_forum.php` | Общение | `/curator/messages` |
| `kurator_pos.php` | Посещения сайта и посещаемость | `/curator/attendance` |
| `kurator_prakt.php` | Курирование практик | `/curator/practice` |
| `kurator_sh.php` | Рабочие документы | `/curator/documents` |
| `kurator_prov_file.php` | Проверка файлов студентов | `/curator/files` |
| `kurator_obiav.php` | Объявления | `/curator/announcements` |
| `kurator_ip.php` | Проверка IP/входов | `/curator/logins` |
| `kurator_ank.php` | Проверка анкетных тестов | `/curator/surveys` |
| `kurator_ank_dan.php` | Проверка анкет | `/curator/surveys` |
| `kurator_stat.php` | Статистика | `/curator/statistics` |
| `kurator_scv.php` | Табличные сводки/экспорт | `/curator/tables` |
| `kurator_grp.php` | Привязка слушателя к группе | `/curator/groups` |
| `kurator_musor.php` | Legacy-корзина скрытых записей | архивные состояния показываются в профильных разделах |

## Принцип доступа

Legacy определял преподавателя/куратора через `tm_nmo_razd.prepod` и `tm_nmo_razd_dop_prepod`. В Laravel доступ ограничивается назначенными куратору `Enrollment.curator_id`; администратор имеет полный доступ. Это сохраняет функции кабинета, но исключает просмотр чужих слушателей.

## Подтверждённые legacy-возможности

- `kurator.php`: программа, список слушателей, последний вход и полная история `tm_login_dat`, текущий NMO-прогресс и архив `tm_nmo_razd_user_arh`;
- `kurator_grp.php`: изменение группы слушателя, причём legacy ограничивал список слушателей разделами, где пользователь был основным или дополнительным преподавателем;
- `kurator_add.php`: добавление ссылок/материалов, редактирование названия/комментария/активности раздела, копирование материала, даты и свойства материала;
- `kurator_prov.php`: просмотр пользовательских изображений/файлов, комментариев и дат, удаление/поворот изображений;
- `kurator_pos.php`: статистика входов и посещаемость по разделам (`nmo_otm_pos`);
- `kurator_prakt.php`: журнал практик `tm_nmo_pract`, дата, выполненная работа, ответ куратора;
- `kurator_forum.php`: общение;
- `kurator_ank*.php`: просмотр анкетных материалов/результатов и истории;
- остальные пункты меню сохранены в новой структуре как отдельные представления над Laravel-данными.
