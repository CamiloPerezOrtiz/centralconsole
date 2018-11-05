CREATE SEQUENCE log_id_seq INCREMENT BY 1 MINVALUE 1 START 1;
CREATE SEQUENCE acl_id_seq INCREMENT BY 1 MINVALUE 1 START 1;
CREATE SEQUENCE target_id_seq INCREMENT BY 1 MINVALUE 1 START 1;
CREATE SEQUENCE administrator_id_seq INCREMENT BY 1 MINVALUE 1 START 1;
CREATE SEQUENCE txtip_id_seq INCREMENT BY 1 MINVALUE 1 START 1;

CREATE TABLE log (
	id INT NOT NULL, 
	description VARCHAR(3) NOT NULL, 
	PRIMARY KEY(id)
);

CREATE TABLE acl (
	id INT NOT NULL, 
	acl_id INT DEFAULT NULL, 
	disabled VARCHAR(15) NOT NULL, 
	name VARCHAR(40) NOT NULL, 
	client TEXT NOT NULL, 
	time TEXT NOT NULL, 
	targetRule TEXT NOT NULL, 
	allowIp VARCHAR(3) NOT NULL, 
	redirectMode VARCHAR(40) NOT NULL, 
	redirect VARCHAR(30) NOT NULL, 
	safeSearch VARCHAR(3) NOT NULL, 
	rewrite VARCHAR(40) NOT NULL, 
	rewriteTime VARCHAR(40) NOT NULL, 
	description VARCHAR(40) NOT NULL, 
	nameGroup VARCHAR(25) NOT NULL, 
	PRIMARY KEY(id)
);

CREATE INDEX IDX_BC806D1244082458 ON acl (acl_id);

CREATE TABLE target (
	id INT NOT NULL, 
	log_id INT DEFAULT NULL, 
	name VARCHAR(255) NOT NULL, 
	domainList VARCHAR(255) NOT NULL, 
	urlList VARCHAR(255) NOT NULL, 
	regularExpression VARCHAR(255) NOT NULL, 
	redirect VARCHAR(255) NOT NULL, 
	description VARCHAR(255) NOT NULL, 
	nameGroup VARCHAR(25) NOT NULL, 
	redirectMode VARCHAR(25) NOT NULL, 
	PRIMARY KEY(id)
);

CREATE INDEX IDX_466F2FFCEA675D86 ON target (log_id);

CREATE TABLE administrator (
	id INT NOT NULL, 
	name VARCHAR(25) NOT NULL, 
	email VARCHAR(50) NOT NULL, 
	password VARCHAR(255) NOT NULL, 
	role VARCHAR(15) NOT NULL, 
	nameGroup VARCHAR(25) NOT NULL, 
	PRIMARY KEY(id)
);

CREATE TABLE txtip (
	id INT NOT NULL, 
	hostname VARCHAR(40) NOT NULL, 
	ip VARCHAR(40) NOT NULL, 
	cliente VARCHAR(40) NOT NULL, 
	description VARCHAR(50) DEFAULT NULL,
	PRIMARY KEY(id)
);

ALTER TABLE acl ADD CONSTRAINT FK_BC806D1244082458 FOREIGN KEY (acl_id) REFERENCES log (id) NOT DEFERRABLE INITIALLY IMMEDIATE;

ALTER TABLE target ADD CONSTRAINT FK_466F2FFCEA675D86 FOREIGN KEY (log_id) REFERENCES log (id) NOT DEFERRABLE INITIALLY IMMEDIATE;

CREATE TABLE aliases (
	id SERIAL PRIMARY KEY,
	name VARCHAR(15) NOT NULL,
	description VARCHAR(40) NOT NULL,
	status TEXT NOT NULL,
	ip TEXT NOT NULL,
	descriptionhost TEXT DEFAULT NULL,
	namegroup TEXT NOT NULL
);

CREATE TABLE nat(
	id SERIAL PRIMARY KEY,
	disabled VARCHAR(45) NOT NULL,
	nordr VARCHAR(40) NOT NULL,
	interface VARCHAR(40) NOT NULL,
	proto VARCHAR(40) NOT NULL,
	srcnot VARCHAR(40) DEFAULT NULL,
	srctype VARCHAR(40) NOT NULL,
	src VARCHAR(40) NOT NULL,
	srcmask VARCHAR(15) NOT NULL,
	srcbeginport VARCHAR(40) NOT NULL,
	dstbeginport_cust VARCHAR(40) NOT NULL,
	srcendport VARCHAR(40) NOT NULL,
	dstendport_cust VARCHAR(40) DEFAULT NULL,
	dstnot VARCHAR(40) NOT NULL,
	dsttype VARCHAR(15) NOT NULL,
	dst VARCHAR(40) NOT NULL,
	dstmask VARCHAR(40) NOT NULL,
	dstendport VARCHAR(40) NOT NULL,
	dstbeginport_cust2 VARCHAR(40) DEFAULT NULL,
	dstendport2 VARCHAR(40) NOT NULL,
	dstendport_cust2 VARCHAR(40) NOT NULL,
	localip VARCHAR(15) NOT NULL,
	localbeginport VARCHAR(40) NOT NULL,
	localbeginport_cust VARCHAR(40) NOT NULL,
	descr VARCHAR(40) NOT NULL,
	nosync VARCHAR(40) DEFAULT NULL,
	natreflection VARCHAR(40) NOT NULL,
	associated_rule_id VARCHAR(40) NOT NULL,
	namegroup VARCHAR(50) NOT NULL,
	position_order SERIAL NOT NULL 
);