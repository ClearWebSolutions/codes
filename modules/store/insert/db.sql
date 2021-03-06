﻿DROP TABLE IF EXISTS `countries`;

CREATE TABLE `countries` (
  `iso1_code` char(2) COLLATE utf8_bin NOT NULL,
  `name_caps` varchar(80) COLLATE utf8_bin NOT NULL,
  `name` varchar(80) COLLATE utf8_bin NOT NULL,
  `iso3_code` char(3) COLLATE utf8_bin DEFAULT NULL,
  `num_code` smallint(6) DEFAULT NULL,
  `paypal_not_supported` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`iso1_code`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

LOCK TABLES `countries` WRITE;

INSERT INTO `countries` (`iso1_code`,`name_caps`,`name`,`iso3_code`,`num_code`,`paypal_not_supported`)
VALUES
	(X'4146','AFGHANISTAN','Afghanistan',X'414647',4,0),
	(X'414C','ALBANIA','Albania',X'414C42',8,0),
	(X'445A','ALGERIA','Algeria',X'445A41',12,1),
	(X'4153','AMERICAN SAMOA','American Samoa',X'41534D',16,0),
	(X'4144','ANDORRA','Andorra',X'414E44',20,0),
	(X'414F','ANGOLA','Angola',X'41474F',24,0),
	(X'4149','ANGUILLA','Anguilla',X'414941',660,0),
	(X'4151','ANTARCTICA','Antarctica',NULL,NULL,1),
	(X'4147','ANTIGUA AND BARBUDA','Antigua and Barbuda',X'415447',28,0),
	(X'4152','ARGENTINA','Argentina',X'415247',32,0),
	(X'414D','ARMENIA','Armenia',X'41524D',51,0),
	(X'4157','ARUBA','Aruba',X'414257',533,0),
	(X'4155','AUSTRALIA','Australia',X'415553',36,0),
	(X'4154','AUSTRIA','Austria',X'415554',40,0),
	(X'415A','AZERBAIJAN','Azerbaijan',X'415A45',31,0),
	(X'4253','BAHAMAS','Bahamas',X'424853',44,0),
	(X'4248','BAHRAIN','Bahrain',X'424852',48,0),
	(X'4244','BANGLADESH','Bangladesh',X'424744',50,0),
	(X'4242','BARBADOS','Barbados',X'425242',52,0),
	(X'4259','BELARUS','Belarus',X'424C52',112,0),
	(X'4245','BELGIUM','Belgium',X'42454C',56,0),
	(X'425A','BELIZE','Belize',X'424C5A',84,0),
	(X'424A','BENIN','Benin',X'42454E',204,0),
	(X'424D','BERMUDA','Bermuda',X'424D55',60,0),
	(X'4254','BHUTAN','Bhutan',X'42544E',64,0),
	(X'424F','BOLIVIA','Bolivia',X'424F4C',68,0),
	(X'4241','BOSNIA AND HERZEGOVINA','Bosnia and Herzegovina',X'424948',70,0),
	(X'4257','BOTSWANA','Botswana',X'425741',72,0),
	(X'4256','BOUVET ISLAND','Bouvet Island',NULL,NULL,1),
	(X'4252','BRAZIL','Brazil',X'425241',76,0),
	(X'494F','BRITISH INDIAN OCEAN TERRITORY','British Indian Ocean Territory',NULL,NULL,1),
	(X'424E','BRUNEI DARUSSALAM','Brunei Darussalam',X'42524E',96,0),
	(X'4247','BULGARIA','Bulgaria',X'424752',100,0),
	(X'4246','BURKINA FASO','Burkina Faso',X'424641',854,0),
	(X'4249','BURUNDI','Burundi',X'424449',108,0),
	(X'4B48','CAMBODIA','Cambodia',X'4B484D',116,0),
	(X'434D','CAMEROON','Cameroon',X'434D52',120,0),
	(X'4341','CANADA','Canada',X'43414E',124,0),
	(X'4356','CAPE VERDE','Cape Verde',X'435056',132,0),
	(X'4B59','CAYMAN ISLANDS','Cayman Islands',X'43594D',136,0),
	(X'4346','CENTRAL AFRICAN REPUBLIC','Central African Republic',X'434146',140,1),
	(X'5444','CHAD','Chad',X'544344',148,0),
	(X'434C','CHILE','Chile',X'43484C',152,0),
	(X'434E','CHINA','China',X'43484E',156,0),
	(X'4358','CHRISTMAS ISLAND','Christmas Island',NULL,NULL,1),
	(X'4343','COCOS (KEELING) ISLANDS','Cocos (Keeling) Islands',NULL,NULL,0),
	(X'434F','COLOMBIA','Colombia',X'434F4C',170,0),
	(X'4B4D','COMOROS','Comoros',X'434F4D',174,0),
	(X'4347','CONGO','Congo',X'434F47',178,0),
	(X'4344','CONGO, THE DEMOCRATIC REPUBLIC OF THE','Congo, the Democratic Republic of the',X'434F44',180,0),
	(X'434B','COOK ISLANDS','Cook Islands',X'434F4B',184,0),
	(X'4352','COSTA RICA','Costa Rica',X'435249',188,0),
	(X'4349','COTE D\'IVOIRE','Cote D\'Ivoire',X'434956',384,0),
	(X'4852','CROATIA','Croatia',X'485256',191,0),
	(X'4355','CUBA','Cuba',X'435542',192,0),
	(X'4359','CYPRUS','Cyprus',X'435950',196,0),
	(X'435A','CZECH REPUBLIC','Czech Republic',X'435A45',203,0),
	(X'444B','DENMARK','Denmark',X'444E4B',208,0),
	(X'444A','DJIBOUTI','Djibouti',X'444A49',262,0),
	(X'444D','DOMINICA','Dominica',X'444D41',212,0),
	(X'444F','DOMINICAN REPUBLIC','Dominican Republic',X'444F4D',214,0),
	(X'4543','ECUADOR','Ecuador',X'454355',218,0),
	(X'4547','EGYPT','Egypt',X'454759',818,0),
	(X'5356','EL SALVADOR','El Salvador',X'534C56',222,0),
	(X'4751','EQUATORIAL GUINEA','Equatorial Guinea',X'474E51',226,0),
	(X'4552','ERITREA','Eritrea',X'455249',232,0),
	(X'4545','ESTONIA','Estonia',X'455354',233,0),
	(X'4554','ETHIOPIA','Ethiopia',X'455448',231,0),
	(X'464B','FALKLAND ISLANDS (MALVINAS)','Falkland Islands (Malvinas)',X'464C4B',238,0),
	(X'464F','FAROE ISLANDS','Faroe Islands',X'46524F',234,0),
	(X'464A','FIJI','Fiji',X'464A49',242,0),
	(X'4649','FINLAND','Finland',X'46494E',246,0),
	(X'4652','FRANCE','France',X'465241',250,0),
	(X'4746','FRENCH GUIANA','French Guiana',X'475546',254,0),
	(X'5046','FRENCH POLYNESIA','French Polynesia',X'505946',258,0),
	(X'5446','FRENCH SOUTHERN TERRITORIES','French Southern Territories',NULL,NULL,0),
	(X'4741','GABON','Gabon',X'474142',266,0),
	(X'474D','GAMBIA','Gambia',X'474D42',270,0),
	(X'4745','GEORGIA','Georgia',X'47454F',268,0),
	(X'4445','GERMANY','Germany',X'444555',276,0),
	(X'4748','GHANA','Ghana',X'474841',288,0),
	(X'4749','GIBRALTAR','Gibraltar',X'474942',292,0),
	(X'4752','GREECE','Greece',X'475243',300,0),
	(X'474C','GREENLAND','Greenland',X'47524C',304,0),
	(X'4744','GRENADA','Grenada',X'475244',308,0),
	(X'4750','GUADELOUPE','Guadeloupe',X'474C50',312,0),
	(X'4755','GUAM','Guam',X'47554D',316,0),
	(X'4754','GUATEMALA','Guatemala',X'47544D',320,0),
	(X'474E','GUINEA','Guinea',X'47494E',324,0),
	(X'4757','GUINEA-BISSAU','Guinea-Bissau',X'474E42',624,0),
	(X'4759','GUYANA','Guyana',X'475559',328,0),
	(X'4854','HAITI','Haiti',X'485449',332,0),
	(X'484D','HEARD ISLAND AND MCDONALD ISLANDS','Heard Island and Mcdonald Islands',NULL,NULL,1),
	(X'5641','HOLY SEE (VATICAN CITY STATE)','Holy See (Vatican City State)',X'564154',336,0),
	(X'484E','HONDURAS','Honduras',X'484E44',340,0),
	(X'484B','HONG KONG','Hong Kong',X'484B47',344,0),
	(X'4855','HUNGARY','Hungary',X'48554E',348,0),
	(X'4953','ICELAND','Iceland',X'49534C',352,0),
	(X'494E','INDIA','India',X'494E44',356,0),
	(X'4944','INDONESIA','Indonesia',X'49444E',360,0),
	(X'4952','IRAN, ISLAMIC REPUBLIC OF','Iran, Islamic Republic of',X'49524E',364,0),
	(X'4951','IRAQ','Iraq',X'495251',368,0),
	(X'4945','IRELAND','Ireland',X'49524C',372,0),
	(X'494C','ISRAEL','Israel',X'495352',376,0),
	(X'4954','ITALY','Italy',X'495441',380,0),
	(X'4A4D','JAMAICA','Jamaica',X'4A414D',388,0),
	(X'4A50','JAPAN','Japan',X'4A504E',392,0),
	(X'4A4F','JORDAN','Jordan',X'4A4F52',400,0),
	(X'4B5A','KAZAKHSTAN','Kazakhstan',X'4B415A',398,0),
	(X'4B45','KENYA','Kenya',X'4B454E',404,0),
	(X'4B49','KIRIBATI','Kiribati',X'4B4952',296,0),
	(X'4B50','KOREA, DEMOCRATIC PEOPLE\'S REPUBLIC OF','Korea, Democratic People\'s Republic of',X'50524B',408,0),
	(X'4B52','KOREA, REPUBLIC OF','Korea, Republic of',X'4B4F52',410,0),
	(X'4B57','KUWAIT','Kuwait',X'4B5754',414,0),
	(X'4B47','KYRGYZSTAN','Kyrgyzstan',X'4B475A',417,0),
	(X'4C41','LAO PEOPLE\'S DEMOCRATIC REPUBLIC','Lao People\'s Democratic Republic',X'4C414F',418,0),
	(X'4C56','LATVIA','Latvia',X'4C5641',428,0),
	(X'4C42','LEBANON','Lebanon',X'4C424E',422,0),
	(X'4C53','LESOTHO','Lesotho',X'4C534F',426,0),
	(X'4C52','LIBERIA','Liberia',X'4C4252',430,0),
	(X'4C59','LIBYAN ARAB JAMAHIRIYA','Libyan Arab Jamahiriya',X'4C4259',434,0),
	(X'4C49','LIECHTENSTEIN','Liechtenstein',X'4C4945',438,0),
	(X'4C54','LITHUANIA','Lithuania',X'4C5455',440,0),
	(X'4C55','LUXEMBOURG','Luxembourg',X'4C5558',442,0),
	(X'4D4F','MACAO','Macao',X'4D4143',446,0),
	(X'4D4B','MACEDONIA, THE FORMER YUGOSLAV REPUBLIC OF','Macedonia, the Former Yugoslav Republic of',X'4D4B44',807,0),
	(X'4D47','MADAGASCAR','Madagascar',X'4D4447',450,0),
	(X'4D57','MALAWI','Malawi',X'4D5749',454,0),
	(X'4D59','MALAYSIA','Malaysia',X'4D5953',458,0),
	(X'4D56','MALDIVES','Maldives',X'4D4456',462,0),
	(X'4D4C','MALI','Mali',X'4D4C49',466,0),
	(X'4D54','MALTA','Malta',X'4D4C54',470,0),
	(X'4D48','MARSHALL ISLANDS','Marshall Islands',X'4D484C',584,0),
	(X'4D51','MARTINIQUE','Martinique',X'4D5451',474,0),
	(X'4D52','MAURITANIA','Mauritania',X'4D5254',478,0),
	(X'4D55','MAURITIUS','Mauritius',X'4D5553',480,0),
	(X'5954','MAYOTTE','Mayotte',NULL,NULL,0),
	(X'4D58','MEXICO','Mexico',X'4D4558',484,0),
	(X'464D','MICRONESIA, FEDERATED STATES OF','Micronesia, Federated States of',X'46534D',583,0),
	(X'4D44','MOLDOVA, REPUBLIC OF','Moldova, Republic of',X'4D4441',498,0),
	(X'4D43','MONACO','Monaco',X'4D434F',492,0),
	(X'4D4E','MONGOLIA','Mongolia',X'4D4E47',496,0),
	(X'4D53','MONTSERRAT','Montserrat',X'4D5352',500,0),
	(X'4D41','MOROCCO','Morocco',X'4D4152',504,0),
	(X'4D5A','MOZAMBIQUE','Mozambique',X'4D4F5A',508,0),
	(X'4D4D','MYANMAR','Myanmar',X'4D4D52',104,0),
	(X'4E41','NAMIBIA','Namibia',X'4E414D',516,0),
	(X'4E52','NAURU','Nauru',X'4E5255',520,0),
	(X'4E50','NEPAL','Nepal',X'4E504C',524,1),
	(X'4E4C','NETHERLANDS','Netherlands',X'4E4C44',528,0),
	(X'414E','NETHERLANDS ANTILLES','Netherlands Antilles',X'414E54',530,0),
	(X'4E43','NEW CALEDONIA','New Caledonia',X'4E434C',540,0),
	(X'4E5A','NEW ZEALAND','New Zealand',X'4E5A4C',554,0),
	(X'4E49','NICARAGUA','Nicaragua',X'4E4943',558,0),
	(X'4E45','NIGER','Niger',X'4E4552',562,0),
	(X'4E47','NIGERIA','Nigeria',X'4E4741',566,0),
	(X'4E55','NIUE','Niue',X'4E4955',570,0),
	(X'4E46','NORFOLK ISLAND','Norfolk Island',X'4E464B',574,0),
	(X'4D50','NORTHERN MARIANA ISLANDS','Northern Mariana Islands',X'4D4E50',580,0),
	(X'4E4F','NORWAY','Norway',X'4E4F52',578,0),
	(X'4F4D','OMAN','Oman',X'4F4D4E',512,0),
	(X'504B','PAKISTAN','Pakistan',X'50414B',586,0),
	(X'5057','PALAU','Palau',X'504C57',585,0),
	(X'5053','PALESTINIAN TERRITORY, OCCUPIED','Palestinian Territory, Occupied',NULL,NULL,0),
	(X'5041','PANAMA','Panama',X'50414E',591,0),
	(X'5047','PAPUA NEW GUINEA','Papua New Guinea',X'504E47',598,0),
	(X'5059','PARAGUAY','Paraguay',X'505259',600,0),
	(X'5045','PERU','Peru',X'504552',604,0),
	(X'5048','PHILIPPINES','Philippines',X'50484C',608,0),
	(X'504E','PITCAIRN','Pitcairn',X'50434E',612,0),
	(X'504C','POLAND','Poland',X'504F4C',616,0),
	(X'5054','PORTUGAL','Portugal',X'505254',620,0),
	(X'5052','PUERTO RICO','Puerto Rico',X'505249',630,0),
	(X'5141','QATAR','Qatar',X'514154',634,0),
	(X'5245','REUNION','Reunion',X'524555',638,0),
	(X'524F','ROMANIA','Romania',X'524F4D',642,0),
	(X'5255','RUSSIAN FEDERATION','Russian Federation',X'525553',643,0),
	(X'5257','RWANDA','Rwanda',X'525741',646,0),
	(X'5348','SAINT HELENA','Saint Helena',X'53484E',654,0),
	(X'4B4E','SAINT KITTS AND NEVIS','Saint Kitts and Nevis',X'4B4E41',659,0),
	(X'4C43','SAINT LUCIA','Saint Lucia',X'4C4341',662,0),
	(X'504D','SAINT PIERRE AND MIQUELON','Saint Pierre and Miquelon',X'53504D',666,0),
	(X'5643','SAINT VINCENT AND THE GRENADINES','Saint Vincent and the Grenadines',X'564354',670,0),
	(X'5753','SAMOA','Samoa',X'57534D',882,0),
	(X'534D','SAN MARINO','San Marino',X'534D52',674,0),
	(X'5354','SAO TOME AND PRINCIPE','Sao Tome and Principe',X'535450',678,1),
	(X'5341','SAUDI ARABIA','Saudi Arabia',X'534155',682,0),
	(X'534E','SENEGAL','Senegal',X'53454E',686,0),
	(X'4353','SERBIA AND MONTENEGRO','Serbia and Montenegro',NULL,NULL,0),
	(X'5343','SEYCHELLES','Seychelles',X'535943',690,0),
	(X'534C','SIERRA LEONE','Sierra Leone',X'534C45',694,0),
	(X'5347','SINGAPORE','Singapore',X'534750',702,0),
	(X'534B','SLOVAKIA','Slovakia',X'53564B',703,0),
	(X'5349','SLOVENIA','Slovenia',X'53564E',705,0),
	(X'5342','SOLOMON ISLANDS','Solomon Islands',X'534C42',90,0),
	(X'534F','SOMALIA','Somalia',X'534F4D',706,0),
	(X'5A41','SOUTH AFRICA','South Africa',X'5A4146',710,0),
	(X'4753','SOUTH GEORGIA AND THE SOUTH SANDWICH ISLANDS','South Georgia and the South Sandwich Islands',NULL,NULL,0),
	(X'4553','SPAIN','Spain',X'455350',724,0),
	(X'4C4B','SRI LANKA','Sri Lanka',X'4C4B41',144,0),
	(X'5344','SUDAN','Sudan',X'53444E',736,0),
	(X'5352','SURINAME','Suriname',X'535552',740,0),
	(X'534A','SVALBARD AND JAN MAYEN','Svalbard and Jan Mayen',X'534A4D',744,0),
	(X'535A','SWAZILAND','Swaziland',X'53575A',748,0),
	(X'5345','SWEDEN','Sweden',X'535745',752,0),
	(X'4348','SWITZERLAND','Switzerland',X'434845',756,0),
	(X'5359','SYRIAN ARAB REPUBLIC','Syrian Arab Republic',X'535952',760,0),
	(X'5457','TAIWAN, PROVINCE OF CHINA','Taiwan, Province of China',X'54574E',158,0),
	(X'544A','TAJIKISTAN','Tajikistan',X'544A4B',762,0),
	(X'545A','TANZANIA, UNITED REPUBLIC OF','Tanzania, United Republic of',X'545A41',834,0),
	(X'5448','THAILAND','Thailand',X'544841',764,0),
	(X'544C','TIMOR-LESTE','Timor-Leste',NULL,NULL,0),
	(X'5447','TOGO','Togo',X'54474F',768,0),
	(X'544B','TOKELAU','Tokelau',X'544B4C',772,0),
	(X'544F','TONGA','Tonga',X'544F4E',776,0),
	(X'5454','TRINIDAD AND TOBAGO','Trinidad and Tobago',X'54544F',780,0),
	(X'544E','TUNISIA','Tunisia',X'54554E',788,0),
	(X'5452','TURKEY','Turkey',X'545552',792,0),
	(X'544D','TURKMENISTAN','Turkmenistan',X'544B4D',795,0),
	(X'5443','TURKS AND CAICOS ISLANDS','Turks and Caicos Islands',X'544341',796,0),
	(X'5456','TUVALU','Tuvalu',X'545556',798,0),
	(X'5547','UGANDA','Uganda',X'554741',800,0),
	(X'5541','UKRAINE','Ukraine',X'554B52',804,0),
	(X'4145','UNITED ARAB EMIRATES','United Arab Emirates',X'415245',784,0),
	(X'4742','UNITED KINGDOM','United Kingdom',X'474252',826,0),
	(X'5553','UNITED STATES','United States',X'555341',840,0),
	(X'554D','UNITED STATES MINOR OUTLYING ISLANDS','United States Minor Outlying Islands',NULL,NULL,0),
	(X'5559','URUGUAY','Uruguay',X'555259',858,0),
	(X'555A','UZBEKISTAN','Uzbekistan',X'555A42',860,0),
	(X'5655','VANUATU','Vanuatu',X'565554',548,0),
	(X'5645','VENEZUELA','Venezuela',X'56454E',862,0),
	(X'564E','VIET NAM','Viet Nam',X'564E4D',704,0),
	(X'5647','VIRGIN ISLANDS, BRITISH','Virgin Islands, British',X'564742',92,0),
	(X'5649','VIRGIN ISLANDS, U.S.','Virgin Islands, U.s.',X'564952',850,0),
	(X'5746','WALLIS AND FUTUNA','Wallis and Futuna',X'574C46',876,0),
	(X'4548','WESTERN SAHARA','Western Sahara',X'455348',732,0),
	(X'5945','YEMEN','Yemen',X'59454D',887,0),
	(X'5A4D','ZAMBIA','Zambia',X'5A4D42',894,0),
	(X'5A57','ZIMBABWE','Zimbabwe',X'5A5745',716,0);

/*!40000 ALTER TABLE `countries` ENABLE KEYS */;
UNLOCK TABLES;

DROP TABLE IF EXISTS `order_details`;

CREATE TABLE `order_details` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `oid` int(11) DEFAULT NULL,
  `pid` int(11) DEFAULT NULL,
  `price` double DEFAULT NULL,
  `qty` int(11) DEFAULT NULL,
  `options` blob,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `order_shipping`;

CREATE TABLE `order_shipping` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `oid` int(11) DEFAULT NULL,
  `shiptoname` varchar(32) DEFAULT NULL,
  `shiptostreet` varchar(100) DEFAULT NULL,
  `shiptostreet2` varchar(100) DEFAULT NULL,
  `shiptocity` varchar(40) DEFAULT NULL,
  `shiptostate` varchar(40) DEFAULT NULL,
  `shiptozip` varchar(20) DEFAULT NULL,
  `shiptocountrycode` varchar(2) DEFAULT NULL,
  `shiptophonenum` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `orders`;

CREATE TABLE `orders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` timestamp NULL DEFAULT NULL,
  `total` double DEFAULT NULL,
  `totalItems` int(11) DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'temp',
  `email` varchar(255) DEFAULT NULL,
  `ipaddress` varchar(15) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `states`;

CREATE TABLE `states` (
  `name` varchar(20) NOT NULL,
  `abv` char(2) NOT NULL,
  `country` char(2) DEFAULT NULL,
  PRIMARY KEY (`abv`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `states` WRITE;

INSERT INTO `states` (`name`,`abv`,`country`)
VALUES
	('Alberta','AB','CA'),
	('Alaska','AK','US'),
	('Alabama','AL','US'),
	('Arkansas','AR','US'),
	('Arizona','AZ','US'),
	('British Columbia','BC','CA'),
	('California','CA','US'),
	('Colorado','CO','US'),
	('Connecticut','CT','US'),
	('District of Columbia','DC','US'),
	('Delaware','DE','US'),
	('Florida','FL','US'),
	('Georgia','GA','US'),
	('Hawaii','HI','US'),
	('Iowa','IA','US'),
	('Idaho','ID','US'),
	('Illinois','IL','US'),
	('Indiana','IN','US'),
	('Kansas','KS','US'),
	('Kentucky','KY','US'),
	('Louisiana','LA','US'),
	('Massachusetts','MA','US'),
	('Manitoba','MB','CA'),
	('Maryland','MD','US'),
	('Maine','ME','US'),
	('Michigan','MI','US'),
	('Minnesota','MN','US'),
	('Missouri','MO','US'),
	('Mississippi','MS','US'),
	('Montana','MT','US'),
	('New Brunswick','NB','CA'),
	('North Carolina','NC','US'),
	('North Dakota','ND','US'),
	('Nebraska','NE','US'),
	('New Hampshire','NH','US'),
	('New Jersey','NJ','US'),
	('Newfoundland and Lab','NL','CA'),
	('New Mexico','NM','US'),
	('Nova Scotia','NS','CA'),
	('Northwest Territorie','NT','CA'),
	('Nunavut','NU','CA'),
	('Nevada','NV','US'),
	('New York','NY','US'),
	('Ohio','OH','US'),
	('Oklahoma','OK','US'),
	('Ontario','ON','CA'),
	('Oregon','OR','US'),
	('Pennsylvania','PA','US'),
	('Prince Edward Island','PE','CA'),
	('Québec','QC','CA'),
	('Rhode Island','RI','US'),
	('South Carolina','SC','US'),
	('South Dakota','SD','US'),
	('Saskatchewan','SK','CA'),
	('Tennessee','TN','US'),
	('Texas','TX','US'),
	('Utah','UT','US'),
	('Virginia','VA','US'),
	('Vermont','VT','US'),
	('Washington','WA','US'),
	('Wisconsin','WI','US'),
	('West Virginia','WV','US'),
	('Wyoming','WY','US'),
	('Yukon Territory','YT','CA');

UNLOCK TABLES;


DROP TABLE IF EXISTS `stock`;

CREATE TABLE `stock` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pid` int(11) DEFAULT NULL,
  `price` double DEFAULT NULL,
  `qty` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;