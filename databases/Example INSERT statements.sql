-- basic example INSERT statements
INSERT INTO team (teamName, nfa, acronym, nickname, eliminated) 
	VALUES ("England", "Football Association", "FA", "Lionesses", FALSE);	-- generic example add team to DB
    
INSERT INTO team (teamName, nfa, acronym, nickname, eliminated) 
	VALUES ("Scotland", "Scottish Football Association", "SFA", "", FALSE);
    
INSERT INTO competitor (fullName, role, title, authorised, teamID)
	VALUES ("Toni Duggan", "Forward", "Ms", TRUE, 1);						-- generic example add competitor to DB
    
INSERT INTO card (competitorID, startDate, endDate, cardStateID)
	VALUES (1, "2017-07-16", "2017-08-06", 1);							-- add card for duration of the competition

INSERT INTO venue (	venueName, stadium)										-- generic example add venue to DB
	VALUES ("Utrecht", "Galgenwaard");

INSERT INTO matchAccess (ID, venueID, team1ID, team2ID, matchDate)
	VALUES (8, 1, 1, 2, "2017-07-19");
    
INSERT INTO venueUsage (venueID, competitorID, dateAccessed, accessGranted)	-- function curdate() returns current date
	VALUES (1, 1, CURDATE(), TRUE);