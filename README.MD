<h3>Task #3 of Babanskyi Oleksii</h3>

<p>MVC application. Interaction with database is performed via Eloquent ORM. Look on general files:</p>

<ul>
    <li><b>/install.sql</b> launch it before you use app. Creates tables and populate it with some data.</li>
    <li><b>/config.php</b> is configuration of app (DB credentials are here).</li>
    <li><b>/routes/web.php</b> contains all ULR-routes of app. It can be considered as Controller.</li>
    <li>Folders /model and /view.</li>
</ul>

<h4>Install</h4>
1) Clone repo to the site directory on your server <br>
2) Create database. You can simply run <b>install.sql</b> on your MySQL-server or create database manually and input credentials in config.php <br>
3) Run in console <b>composer install</b>