const express = require('express');
const session = require('express-session');
const bodyParser = require('body-parser');
const mysql = require('mysql');

const app = express();
const dbConn = setupDatabaseConnection();

const BasicRouter = require('./basicRouter');
const ApiRouter = require('./apiRouter');

const basicRouter = new BasicRouter(app, dbConn);
const apiRouter = new ApiRouter(app, dbConn);

setupDatabase();
setupApp();
addBasicRouting();
addApiRouting();
startListening();

function setupDatabaseConnection() {
  var conn = createConnection()
  conn.connect()
  return conn
}

function createConnection() {
  return mysql.createConnection({
    host: 'localhost',
    database: 'node_db',
    user: 'root',
    password: 'Test123!',
    multipleStatements: true
  });
}

function setupDatabase() {
  dropPreviousAndCreateTable();
  populateDBWithInitialData();
}

function dropPreviousAndCreateTable() {
  dbConn.query('DROP TABLE IF EXISTS products;');
  dbConn.query('CREATE TABLE products (id INT AUTO_INCREMENT PRIMARY KEY, name VARCHAR(255) NOT NULL, is_purchased BOOLEAN NOT NULL DEFAULT 0);');
}

function populateDBWithInitialData() {
  dbConn.query("INSERT INTO products (name) VALUES ('item1'), ('item2'), ('item3'), ('item4'), ('item5'), ('item6'), ('item7'), ('item8'), ('item9'), ('item10'), ('item11'), ('item12'), ('item13') , ('item14'), ('item15');");
}

function setupApp() {
  useBodyParser()
  useSession();
  useStaticFiles();
  setViewEngine();
  useHeaders();
}

function useBodyParser() {
  app.use(bodyParser.json());
}

function useSession() {
  app.use(session({
    secret: 'session-secret',
    saveUninitialized: true,
    resave: true
  }));
}

function useStaticFiles() {
  app.use(express.static('public'));
}

function setViewEngine() {
  app.set('view engine', 'ejs');
  app.set('views', 'views');
}

function useHeaders() {
  app.use(function (req, res, next) {
    res.header("Access-Control-Allow-Origin", "*");
    res.header("Access-Control-Allow-Headers", "Origin, X-Requested-With, Content-Type, Accept");
    res.header("Cache-Control", "no-store, private, no-cache, must-revalidate");
    res.header("Pragma", "no-cache");
    res.header("Expires", "0");
    next();
  })
}

function addBasicRouting() {
  basicRouter.init();
}

function addApiRouting() {
  apiRouter.init();
}

function startListening() {
  app.listen(3000, () => {
    console.log("server started");
  });
}