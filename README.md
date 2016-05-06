
#Pre-requisites:
- All actions need to be done client side using AJAX, refreshing the page is not acceptable.
- REST API. Make it possible to perform all user actions via the API, including authentication.


#Application Briefing:

##Write an application for the input of calories

- User must be able to create an account and log in
- Implement at least two roles with different permission levels 
	- A regular user would only be able to CRUD on his owned records.
	- An admin would be able to CRUD on all records and users.
	- A user manager would be able to CRUD users (optional).

- When logged in, user can see a list of his meals and calories.
	- User enters calories manually, no auto calculations!
	- He should be able to edit and delete

- Each entry has a date, time, text, and num of calories

- Filter by dates from-to, time from-to 
	- E.g. how much calories have I had for lunch each day in the last month, if lunch is between 12 and 15h

- User setting: Expected number of calories per day
- When displayed, it goes green if the total for that day is less than expected number of calories per day, otherwise goes red


#Important Information:
- In any case you should be able to explain how a REST API works and demonstrate that by creating functional tests that use the REST Layer directly.
- Bonus: unit tests!
- You will not be marked on graphic design, however, do try to keep it as tidy as possible.
- Please keep in mind that this is the project that will be used to evaluate your skills. The project will be evaluated as if you are delivering it to a customer. We expect you to make sure that the app is fully functional and doesn’t have any obvious missing pieces. The deadline for the project is 2 weeks from today (May 16th).
