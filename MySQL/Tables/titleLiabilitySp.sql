DROP TABLE titleLiabilitySp;
CREATE TABLE titleLiabilitySp (
  id integer(10) primary key auto_increment,
  transType varchar(20),
  titleNo integer(10),
  accountNo integer(10),
  threshold integer(10) DEFAULT 0,
  startDate datetime,
  discount decimal(6,2) DEFAULT 0.00,
  rate decimal(6,2) DEFAULT 0.00,
  net boolean DEFAULT 0,
  whenShipped boolean DEFAULT 0,
  updatedBy 	varchar(20), 
  userNo		integer(10),
  lastUpdated datetime,
  INDEX titleNo (titleNo), 
  INDEX accountNo (accountNo),
  INDEX transType (transType));