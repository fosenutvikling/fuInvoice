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
  app_c_id MEDIUMINT UNSIGNED NOT NULL, /* external customer id*/
  invoice_id INT UNSIGNED NOT NULL, /* the invoice number used*/

  kid INT UNSIGNED NOT NULL,

  time_created TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  time_sent TIMESTAMP NOT NULL DEFAULT 0,
  time_due_date TIMESTAMP NOT NULL DEFAULT 0,

  invoice_type ENUM('draft','invoice','credit','reminder','dept') NOT NULL DEFAULT 'draft',

  sender_orgnumber CHAR(9) NOT NULL DEFAULT '000000000',
  sender_name VARCHAR(100) NOT NULL DEFAULT '',
  sender_address VARCHAR(255) NOT NULL DEFAULT '',
  sender_zip CHAR(4) NOT NULL DEFAULT '0000',
  sender_location VARCHAR(255) NOT NULL DEFAULT '',
  sender_ref VARCHAR(255) NOT NULL DEFAULT '', /*sender name*/

  invoice_ref VARCHAR(255) NOT NULL DEFAULT '',

  receiver_orgnumber CHAR(9) NOT NULL DEFAULT '000000000',
  receiver_name VARCHAR(100) NOT NULL DEFAULT '',
  receiver_address VARCHAR(255) NOT NULL DEFAULT '',
  receiver_zip CHAR(4) NOT NULL DEFAULT '0000',
  receiver_location VARCHAR(255) NOT NULL DEFAULT '',
  receiver_ref VARCHAR(255) NOT NULL DEFAULT '', /*sender name*/
  receiver_mail VARCHAR(255) NOT NULL DEFAULT '', /*sender name*/

  FOREIGN KEY(app_id) REFERENCES Application(app_id)
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
  app_product_id INT UNSIGNED NOT NULL DEFAULT 0, /* fk application*/

  description TEXT NOT NULL DEFAULT '',
  quantity FLOAT UNSIGNED NOT NULL DEFAULT 0,
  price FLOAT NOT NULL DEFAULT 0,
  discount FLOAT UNSIGNED NOT NULL DEFAULT 0,
  mva SMALLINT UNSIGNED NOT NULL DEFAULT 25,

  FOREIGN KEY(invoice_id) REFERENCES Invoice(id)
  ON UPDATE CASCADE
  ON DELETE CASCADE,

  FOREIGN KEY(app_product_id) REFERENCES Application(app_id)
  ON UPDATE CASCADE
  ON DELETE CASCADE,

  PRIMARY KEY(line_id),
  KEY INVOICE_ID(invoice_id)

)ENGINE=InnoDB;

COMMIT;