# Santa12 - Secret Santa

![Alt text](docs/login.png?raw=true "Login page")
Santa12 is a Secret Santa web application I built for the company I worked with at that time.
Inspired by Facebook's user interface and seeing how people were having lots of fun posting and 
commenting, I patterned the same UI and added some warm, christmassy touch. The same inspiration
motivated me to write this program from scratch and deploy the first version within 2 weeks.
This is not requested by the company but this is my initiative to setup a channel where everyone
feels safe posting and commenting anything on their mind regarding their wishes.

Wish Feed page:

![Alt text](docs/wish_feed.png?raw=true "Wish Feed page")

Profile page:

![Alt text](docs/profile.png?raw=true "Profile page")

Notifications:

![Alt text](docs/notifs.png?raw=true "Notifications")

## Tools used
1. PHP 5
2. CodeIgniter - MVC framework for writing PHP web apps
3. MySQL 5.5
4. XAMPP - easy setup of all software components


## Setup
Local environment:
1. Run XAMPP, ensure to run Apache and MySQL in it.
   1. Make sure no other server container is using port 80 (e.g. `apachectl status`).
2. Deploy the application (this whole project) into `/xampp/htdocs` folder.
3. Restore MySQL database from `/db/mysql_dump_santa12_nodata.db`
4. On your browser, type `http://localhost/santa12`.
5. Login using the following credentials: `delfino.jl@ntsp.nec.o.jp`/`welcome`

