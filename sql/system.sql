START TRANSACTION;
CREATE TABLE IF NOT EXISTS Application
(
  app_id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  api_key CHAR(64) NOT NULL,
  role ENUM ('admin','user') NOT NULL DEFAULT 'user',
  reader_api_key CHAR(64) NOT NULL,

  PRIMARY KEY(app_id),
  KEY KEY_API(api_key)
)ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS ApplicationWhiteList
(
  app_id INT UNSIGNED NOT NULL,
  ip INT UNSIGNED NOT NULL DEFAULT 2130706433,/*there's no place like home*/

  FOREIGN KEY(app_id) REFERENCES Application(app_id)
  ON UPDATE CASCADE
  ON DELETE CASCADE,

  PRIMARY KEY(app_id,ip)

)ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS Invoice
(
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  app_id INT UNSIGNED NOT NULL,
  app_user_id MEDIUMINT UNSIGNED NOT NULL, /*16777215*/
  app_receiver_id MEDIUMINT UNSIGNED NOT NULL, /* external customer id*/
  invoice_id INT UNSIGNED NOT NULL, /* the invoice number used*/

  kid INT UNSIGNED NOT NULL,
  bank_account_number CHAR(11) NOT NULL DEFAULT '00000000000',

  time_created TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  time_sent TIMESTAMP NOT NULL DEFAULT 0,
  time_due_date DATE NOT NULL DEFAULT 0,
  description TEXT NOT NULL DEFAULT '',

  pid INT UNSIGNED DEFAULT NULL, /*parent invoice, if reminder sent (var only set for type=reminder*/
  rnumber TINYINT(1) NOT NULL DEFAULT 0, /* reminder number. only set for type=reminder*/

  invoice_type ENUM('draft','invoice','credit','reminder','dept','paid') NOT NULL DEFAULT 'draft',

  sender_orgnumber VARCHAR(20) NOT NULL DEFAULT '',
  sender_name VARCHAR(100) NOT NULL DEFAULT '',
  sender_address VARCHAR(255) NOT NULL DEFAULT '',
  sender_zip CHAR(4) NOT NULL DEFAULT '0000',
  sender_location VARCHAR(255) NOT NULL DEFAULT '',
  sender_ref VARCHAR(255) NOT NULL DEFAULT '', /*sender name*/
  sender_mail VARCHAR(255) NOT NULL DEFAULT '',
  sender_webpage VARCHAR(255) NOT NULL DEFAULT '',
  sender_vat_registered TINYINT(1) NOT NULL DEFAULT 0,/* whether sender is vat-registered (append MVA at invoice*/

  invoice_ref VARCHAR(255) NOT NULL DEFAULT '',/* reference to other person within company*/

  receiver_orgnumber VARCHAR(20) NOT NULL DEFAULT '',
  receiver_name VARCHAR(100) NOT NULL DEFAULT '',
  receiver_address VARCHAR(255) NOT NULL DEFAULT '',
  receiver_zip CHAR(4) NOT NULL DEFAULT '0000',
  receiver_location VARCHAR(255) NOT NULL DEFAULT '',
  receiver_ref VARCHAR(255) NOT NULL DEFAULT '', /*sender name*/
  receiver_mail VARCHAR(255) NOT NULL DEFAULT '', /*sender mail*/

  FOREIGN KEY(app_id) REFERENCES Application(app_id)
  ON UPDATE CASCADE
  ON DELETE CASCADE,

  FOREIGN KEY(pid) REFERENCES Invoice(id)
  ON UPDATE CASCADE
  ON DELETE CASCADE,


  PRIMARY KEY(id),
  KEY APPLICATION_ID(app_id),
  KEY INVOICE_TYPE(invoice_type)


)ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS Invoice_line
(
  line_id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  invoice_id INT UNSIGNED NOT NULL,/*fk invoice */
  app_product_id INT UNSIGNED NOT NULL DEFAULT 0,/* refer to external product id saved on client side*/
  app_account_number INT UNSIGNED NOT NULL DEFAULT 0,/*The application account number to link to (konto)*/

  description TEXT NOT NULL DEFAULT '',
  quantity FLOAT UNSIGNED NOT NULL DEFAULT 0,
  price FLOAT NOT NULL DEFAULT 0,
  discount FLOAT UNSIGNED NOT NULL DEFAULT 0,
  mva SMALLINT UNSIGNED NOT NULL DEFAULT 25,

  FOREIGN KEY(invoice_id) REFERENCES Invoice(id)
  ON UPDATE CASCADE
  ON DELETE CASCADE,


  PRIMARY KEY(line_id),
  KEY INVOICE_ID(invoice_id),
  KEY APPLICATION_ID(app_account_number)

)ENGINE=InnoDB;

COMMIT;