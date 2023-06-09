# Market Price Chart

Market Price Chart - это плагин для WordPress, который отображает график рыночной цены, используя данные от API blockchain.info.

## Особенности

- Отображает график рыночной цены с различными временными интервалами (30, 60, 180 дней, 1 год, 2 года, все время)
- Использует API blockchain.info для получения данных графика
- Возможность масштабировать и прокручивать график

### Данный плагин у меня не получилось доделать, не хватает знаний и опыта, график не выводится и временные отрезки не устанавливаются, все же я решил расписать что должен делать этот плагин и что я для этого использовал.

## Установка

1. Скачайте архив плагина и установите его на ваш сайт WordPress.
2. Активируйте плагин через меню "Плагины" в WordPress.

## Использование

1. Добавьте шорткод `[market_price_chart]` в контент страницы или записи, где вы хотите отобразить график рыночной цены.
2. График будет автоматически загружен и отображен на странице с кнопками для выбора временных интервалов.

## Как это работает

1. Плагин использует API blockchain.info для получения данных о рыночной цене.
2. При активации плагина данные загружаются и сохраняются в базе данных сайта.
3. Шорткод `[market_price_chart]` отображает график на основе данных из базы данных сайта с использованием библиотеки Chart.js.
4. Пользователь может выбирать разные временные интервалы с помощью кнопок, и график будет обновляться соответственно.

## Зависимости

Плагин использует следующие JavaScript-библиотеки:

- Chart.js (v2.9.4) - для рисования графика
- Moment.js (v2.29.4) - для работы с датами
- Chartjs-adapter-moment (v0.1.2) - адаптер для работы с Moment.js в Chart.js
- Chartjs-plugin-zoom (v0.7.7) - плагин для возможности масштабировать и прокручивать график

## Автор

alexW3c_maker
