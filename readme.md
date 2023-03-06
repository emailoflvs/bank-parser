Script for parsing the exchange rate of the Central Bank of the Russian Federation.

1. Using open methods (XML_daily and XML_dynamic) of the Central Bank of the Russian Federation (http://www.cbr.ru/development/SXML/)
get and fill the database.
There is a currency table in the database:
valueID - currency identifier that the method returns (example: R01010)
numCode - numeric currency code (example: 036)
charCode - alphabetic currency code (example: AUD)
name - currency name (example: Australian dollar)
value - rate value (example: 43.9538)
date - the date the course was published (can be in UNIX format or ISO 8601)


2. A REST API method has been implemented that returns the exchange rate(s) for the passed valueID for the specified period:
  parsingFrom
  parsingTo
  valueID (currency id)
  Parameters are passed using the GET method.

3. Implemented authorization and registration page.
The main page contains a table with a list of currencies and data on these currencies for the date specified in the field/selector.
Automatically when the page is opened. there is an import of quotes for X days and the dynamics of quotes for the test period.
All test data is specified in Controller@index.
