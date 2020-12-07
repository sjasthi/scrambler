# Scrambler Games

The scrambler games are a part of a project for a non-profit Indic Languages school in Minneapolis to teach children languages through games to encourage critical thinking. There are 7 different varieties of games, each of which take a number of words as input and scramble them in various ways on the left side of the page, with empty spaces for the player to unscramble the words on the right side of the page

## Installation

Copy the /scrambler directory to your server and run the scrambler/sql/scrambler_db file on your sql server to create the database for storing the words and word sets

## Usage

There are seven different games. Each of them throw errors if a special character or number is entered (aside from comma in Dabble Plus), only one word is entered, nothing is entered, a duplicate word is entered, or if the input does not match the specific restrictions for the structure of the game. Any spaces input will be discarded/ignored.

Dabble - Takes words in ascending order of length and scrambles all of the together. Can be made to be center, left, or right justified (pyramid, step down and step up respectively).

Dabble Plus - Same as dabble, but allows for commas to separate multiple words on the same line and displays the multiple word lines with different background colors for the tiles to indicate multiple words

Lines - Takes in any number of words of any variety of lengths and scrambles them individually, leaving a space between each line

Stacks - Takes in any number of words of any variety of lengths and scrambles them all together. Can be configured to display center, left, or right justified

Squares - Takes in words of matching lengths and scrambles them all together

Swimlanes - Takes in words of matching lengths and has options to scramble columns, or scramble rows and then columns. Either way, each column contains one letter from each word. Display options to display repeating letters in a column or display each letter only once in each column regardless of how many times it appears

Shapes - Works similarly to Swimlanes but places each row in a shape (circle or rectangle)

Each game allows admins to create a printable version and to save the words to the database

The home page provides links to games saved within the database that are playable online

The random button pulls up a random game for the user to play

The admin page currently provides only Wordset options, which allow an admin to modify wordsets, delete wordsets, or play specific wordsets. The other pages within admin currently have placeholders indicating that they are to be completed yet

Login currently only handles one set of credentials, which are prepopulated and allow the user logging in admin rights. Without these, the only functionality available is to play games online

## License
Need to discuss with Professor, Creative Commons I think?