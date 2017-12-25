# 7 wonders score

### Description  
This is a small "quick and dirty" (working to clean it up) web app to keep a history of scores for the board game [7 wonders](https://en.wikipedia.org/wiki/7_Wonders_(board_game)).  

### System Requirements:  
1. PHP 7+  
2. Mysql 5.6+  
3. Only tested on ubuntu.

### How to install:  

1. Clone this repo:  
2. Create a database.  
3. Run `composer install` 
4. Run `php bin/console app:install` and fill in the fields you are required to fill in and hit submit.
5. There might be some issues with the folder permissions for `var`. Make it writable if it happens.
6. Enjoy.  

### How to use
You can manage the players, wonders and scoring categories from the backend.  
The install sql comes with the standard wonders and scoring categories and the ones from the Leaders and Cities extension packs.  
You can add more if needed. The scoring categories that come from the extension packs can be marked as disabled when submitting a score.  
There is no "delete" action so far for anything and no "edit" for a submitted game. If you screw up, you clean it up manually.  

### Licence:
Copyright 2018 Marius Strajeru

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.  

### Bug Reports 
Report any bugs or feature requests here: https://github.com/tzyganu/7-wonders-score/issues
