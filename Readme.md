<a href="https://codeclimate.com/github/DaaN88/php-project-lvl2/maintainability"><img src="https://api.codeclimate.com/v1/badges/279c8494dbed6cb17b66/maintainability" /></a>
![php_сodesniffer](https://github.com/DaaN88/php-project-lvl2/workflows/php_%D1%81odesniffer/badge.svg)
<a href="https://codeclimate.com/github/DaaN88/php-project-lvl2/test_coverage"><img src="https://api.codeclimate.com/v1/badges/279c8494dbed6cb17b66/test_coverage" /></a><br/>

Пакет представляет собой консольную программу, определяющую разницу между двумя структурами данных.<br/>
Возможности программы: <br/>
- поддержка разных входных форматов: yaml и json;<br/>
- генерация отчета в виде plain text, stylish и json;<br/>

Установка пакета:<br/>
- глобально: <code>composer global require anton-shvedov88/difference_calculator</code>;<br/>
- в случае ошибки, при глобальной установке на линуксе (работа пакета проверялась на Debian 10), добавить master-dev:<br/>
<code>composer global require anton-shvedov88/difference_calculator:master-dev</code> <br/>

Требования:<br/>
- php-version: от 7.0;<br/>
- composer-version: от 1.10.6;<br/>

Примеры работы пакета:<br/>
- сверка плоских json-файлов: <br/>
[![asciicast](https://asciinema.org/a/BiXU503jIuWW9jPQDZAxuMLM6.svg)](https://asciinema.org/a/BiXU503jIuWW9jPQDZAxuMLM6)<br/>
- сверка плоских yaml-файлов: <br/>
[![asciicast](https://asciinema.org/a/3ZCurQmW4Ag3cnB0VdAK8GEVZ.svg)](https://asciinema.org/a/3ZCurQmW4Ag3cnB0VdAK8GEVZ)<br/>
- сверка вложенных json-файлов: <br/>
[![asciicast](https://asciinema.org/a/6pTFf9P7PUN7xA3YpYrTFHtEJ.svg)](https://asciinema.org/a/6pTFf9P7PUN7xA3YpYrTFHtEJ)<br/>
- вывод в plain-формате: <br/>
[![asciicast](https://asciinema.org/a/lrOYTPMQKPhzTVvKVDglbUvhU.svg)](https://asciinema.org/a/lrOYTPMQKPhzTVvKVDglbUvhU)<br/>
- вывод в формате JSON:<br/>
[![asciicast](https://asciinema.org/a/wIae2aEXnHjSTlKKj4EmMydvd.svg)](https://asciinema.org/a/wIae2aEXnHjSTlKKj4EmMydvd)
