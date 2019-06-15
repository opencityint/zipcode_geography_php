CREATE TABLE `zipcodes` (
  `zip` char(5) NOT NULL,
  `city` varchar(70) NOT NULL,
  `state` varchar(5) NOT NULL,
  `latitude` float NOT NULL,
  `longitude` float NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

INSERT INTO `zipcodes` (`zip`, `city`, `state`, `latitude`, `longitude`) VALUES
('00215', 'Portsmouth', 'NH', 43.0059, -71.0132),
('00501', 'Holtsville', 'NY', 40.9223, -72.6371);

ALTER TABLE `zipcodes`
  ADD PRIMARY KEY (`zip`),
  ADD KEY `latitude` (`latitude`),
  ADD KEY `longitude` (`longitude`),
  ADD KEY `zip` (`zip`)
COMMIT;
