/*
 * @package     Com_addresses
 * @version     1.3.1
 * @copyright   Copyright (C) 2025. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 * @author      René Kreijveld <email@renekreijveld.nl> - https://renekreijveld.nl
 */

/*
 * Mysql init file
 * This SQL script loads sample data into the #__addresses database table
 */

INSERT INTO `#__addresses` (`catid`, `created_by`, `state`, `ordering`, `title`, `address`, `postcode`, `city`, `country`) VALUES
(0,	0,	1,	1,	'Bakkerij De Zon',	'Dorpsstraat 12',	'1234 AB',	'Amersfoort',	'Nederland'),
(0,	0,	1,	2,	'Café De Brug',	'Marktplein 8',	'2345 BC',	'Arnhem',	'Nederland'),
(0,	0,	1,	3,	'Slagerij Van Dijk',	'Hoofdweg 34',	'3456 CD',	'Breda',	'Nederland'),
(0,	0,	1,	4,	'Kapsalon Bella',	'Kerkstraat 56',	'4567 DE',	'Eindhoven',	'Nederland'),
(0,	0,	1,	5,	'Dierenarts De Poot',	'Lijsterlaan 78',	'5678 EF',	'Groningen',	'Nederland'),
(0,	0,	1,	6,	'Boekhandel Letter',	'Stationsstraat 9',	'6789 FG',	'Haarlem',	'Nederland'),
(0,	0,	1,	7,	'Fietsenwinkel Fast',	'Molenweg 11',	'7890 GH',	'Zwolle',	'Nederland'),
(0,	0,	1,	8,	'Supermarkt Jumbo',	'Prinsenstraat 22',	'8901 HI',	'Maastricht',	'Nederland'),
(0,	0,	1,	9,	'Apotheek Vita',	'Beukenlaan 45',	'9012 IJ',	'Utrecht',	'Nederland'),
(0,	0,	1,	10,	'Tandarts Smiley',	'Havenstraat 67',	'1023 JK',	'Tilburg',	'Nederland'),
(0,	0,	1,	11,	'Groenteboer Vers',	'Esdoornlaan 89',	'1124 KL',	'Leeuwarden',	'Nederland'),
(0,	0,	1,	12,	'Bloemist Fleur',	'Rozensingel 3',	'1225 LM',	'Nijmegen',	'Nederland'),
(0,	0,	1,	13,	'Snackbar Tasty',	'Schoolstraat 14',	'1326 MN',	'Dordrecht',	'Nederland'),
(0,	0,	1,	14,	'Garage De Band',	'Industrieweg 16',	'1427 NO',	'Alkmaar',	'Nederland'),
(0,	0,	1,	15,	'Makelaar Huisvast',	'Notarisstraat 20',	'1528 OP',	'Apeldoorn',	'Nederland'),
(0,	0,	1,	16,	'Kinderopvang Blij',	'Speelplein 33',	'1629 PQ',	'Almere',	'Nederland'),
(0,	0,	1,	17,	'Modezaak Trendy',	'Modeweg 44',	'1730 QR',	'Hilversum',	'Nederland'),
(0,	0,	1,	18,	'Sportschool Fit',	'Stadionlaan 55',	'1831 RS',	'Enschede',	'Nederland'),
(0,	0,	1,	19,	'Restaurant De Smid',	'Havenkade 66',	'1932 ST',	'Deventer',	'Nederland'),
(0,	0,	1,	20,	'Drukkerij Printo',	'Drukkerijstraat 77',	'2033 TU',	'Venlo',	'Nederland'),
(0,	0,	1,	21,	'Kunstgalerij Arté',	'Museumplein 88',	'2134 UV',	'Gouda',	'Nederland'),
(0,	0,	1,	22,	'Bouwbedrijf Stevig',	'Bouwstraat 99',	'2235 VW',	'Roermond',	'Nederland'),
(0,	0,	1,	23,	'IT Support Byte',	'Technohof 100',	'2336 WX',	'Lelystad',	'Nederland'),
(0,	0,	1,	24,	'Schoonmaak Sterk',	'Schoonmaakweg 101',	'2437 XY',	'Zutphen',	'Nederland');