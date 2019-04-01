Turing backend challenge quick overview.
============

Hello, guys from Turing. 
I had some free time on the previous week, so, I had a chance to spend it on this project.
> I've spent 23 hours on it. 

## What's done?
First of all I've focused myself on checking DB structure. 

I've found that it's using MyISAM tables and have no constraints, foreign keys, etc.
So, first of all I've focused myself on fixing this issues. 
Results can be found in general_cleanup migration.

**General features:**
- Registration
- Authentication
  - Login
  - Refresh token
  - Logout
- Products
  - Filtering by Department, Category
  - Searching by Product name, description
  - Offset quering - items_per_page is defined in turing config file. 
- Customer 
  - Profile show
  - Profile update
- Shopping cart add product

## Project Stack
- Laravel 5.8
- JWT
- CORS 
- Mysql
- Redis 
- Capistrano
- Stackify


## Resources and links
1. Stackify access. I'll send credentials via Google Hangouts to mr. Khuong Nguyen.
2. I had no enough time to do my best with documenting API, but I've used POSTMAN to test it, so, it has feature
which allow you to publish requests description in readable format. 
[Collection link](https://vitrinazaimov.postman.co/collections/5767740-860de05d-0c26-4e10-8a5c-da67496305ec?workspace=20d59130-c49e-49e8-914a-8635950c4f06#a53e1385-0996-456d-8d2e-34a7c3a8e0f1)
3. Code is deployed on http://185.205.210.3
3. Api is accessable on http://185.205.210.3/api
4. It's hosted on https://www.vps.ag/lightkvm (cheap vm)


## Advanced requirements
**Designing system which can support 1.000.000 daily active users.**

From my point of view everything almost ready for that. 
To support such number of users\request there should be:
- Load balancer (such as HAProxy) should be setup and configured and placed in front. 
- Several backend server instances. Sessions should not be attached to concrete server (I'm using JWT for that purposes) 
- Can be replicated to separate reads and writes load. Laravel supports read\write connections. 

**A half of the daily active users comes from United States.**
 - In this case you def need to place part of your servers in US.
 - Use DNS for routing to your servers based on your client region
 - You can create several DB instances for writing and use hash function based on client's data to calculate db instance or caching server. 


## Work log
- Tuesday - 1:45 on writing database migrations
- Wednesday - 2:20 writing products search
- Tuesday  - 2:40 products search
- Friday - 4:10 authentication, registration, customer update; 0:45 - project scope clarification; 2:40 writing tests
- Sartuday - 1:15 tests and comments, 1:00 stress test seeder
- Sunday - 1:30 setting up deploy + apm, 2:25 - fixing bugs, seeder, etc
- Monday - 2:45 read.me, postman, final testing. 




