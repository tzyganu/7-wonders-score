# 7 wonders score

### Description  
This is a small "quick and dirty" web app to keep a history of scores for the board game [7 wonders](https://en.wikipedia.org/wiki/7_Wonders_(board_game)).  

### System Requirements:  
1. PHP 7+  
2. Mysql 5.6+  
3. Only tested on ubuntu.

### How to install:  
(some manual work is involved. There is no pretty installer for now)  

1. Create a folder on your server. Let's call it `wonders`. (feel free to change the name)    
2. Navigate to that folder.  
3. Clone this repo: `git clone https://github.com/tzyganu/7-wonders-score.git .`  
4. Create a database. Let's call it `wondersdb`. (feel free to change the name)  
5. Import the file `db/install.sql` into your newly created database.  
6. Run `composer install` 
7. Run `php bin/console app:install` and fill in the fields you are required to fill in and hit submit.
12. You should be done. Try it in the browser.  

### How to use
You can manage the players, wonders and scoring categories from the backend.  
The install sql comes with the standard wonders and scoring categories and the ones from the Leaders and Cities extension packs.  
You can add more if needed. The scoring categories that come from the extension packs can be marked as disabled when sumitting a score.  
There is no "delete" action so far for anything and no "edit" for a submitted game. If you screw up, you clean it up manually.  

### Licence:
Copyright 2018 Marius Strajeru

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.  

### Bug Reports 
Report any bugs or feature requests here: https://github.com/tzyganu/7-wonders-score/issues
