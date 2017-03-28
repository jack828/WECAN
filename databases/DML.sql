-- ALL VALUES TO BE SUPPLIED BY USER HAVE PLACEHOLDERS MARKED BY <>


-- 2.3.1 c)	register, issue a card and authorise an individual competitor
-- 			who arrives during the tournament
INSERT INTO competitor (fullName, role, title, authorised, teamID) 	-- register new competitor
	VALUES (<newName>, <newRole>, <newTitle>, TRUE, <newTeamID>);

INSERT INTO card (competitorID, startDate, endDate, cardStateID)		-- issue new card (valid from today)
	VALUES (<newCompetitor>, CURDATE(), "2017-08-06", 1);


-- 2.3.1 d)	de-register, expire card and cancel authorisations for a
-- 			competitor leaving during the tournament
UPDATE card, competitor 					-- expire current card belonging to competitor
SET card.endDate = CURDATE(),				-- update end date for recording purposes
	card.cardStateID = 2,
	competitor.authorised = FALSE				-- de-register competitor
WHERE card.competitorID = competitor.ID
	AND card.cardStateID = 1
	AND competitor.ID = <leavingCompetitor>;


-- 2.3.1 e) retrieve authorisations for access to a specified match/venue
SELECT teamName, fullName, role, title
FROM team, competitor, matchAccess
WHERE competitor.teamID = team.ID
	AND (team.ID = matchAccess.team1ID OR team.ID = matchAccess.team2ID)
	AND matchAccess.ID = <matchID>;


-- 2.3.1 f)	respond to requests for all entries to a venue for a match
SELECT IF(EXISTS(
	SELECT *
    FROM matchAccess, team, competitor, card, venue
	WHERE (matchAccess.team1ID = team.ID OR matchAccess.team2ID = team.ID)
		AND team.ID = competitor.teamID
		AND competitor.ID = card.competitorID
		AND card.ID = <thisCard>
		AND card.cardStateID = 1
		AND matchAccess.matchDate >= card.startDate
		AND matchAccess.venueID = venue.ID
		AND venue.ID = <thisVenue>
		AND matchAccess.matchDate = CURDATE()
),1,0) AS result;							-- returns true if VALID card belongs to a competitor in a team that is scheduled to play TODAY at THIS venue

-- 2.3.1 g)	add authorisations to access a venue for a match for a team
INSERT INTO matchAccess (ID, venueID, team1ID, team2ID, matchDate)				-- add match to database, authorises team access through referential integrity
	VALUES (<matchNumber>, <matchVenue>, <team1ID>, <team2ID>, <matchDate>);

-- 2.3.1 h)	change authorisations to access a venue for a match for a team,
-- 			if the venue is changed
UPDATE matchAccess				-- change venue
SET venueID = <newVenueID>
WHERE ID = <thisMatch>;


-- 2.3.1 i)	expire the cards of all competitors of a team after it is eliminated
UPDATE card, competitor, team
SET team.eliminated = TRUE,
	card.cardStateID = 2,				-- expire all competitor cards for eliminated team
	card.endDate = CURDATE(),					-- update all cards to reflect date of elimination
  competitor.authorised = FALSE
WHERE card.competitorID = competitor.ID
	AND competitor.teamID = team.ID
	AND team.ID = <thisTeamID>;


-- 2.3.1 j)	replace lost/stolen/destroyed cards and ensure appropriate authorisations for new card
UPDATE card									-- cancel all previous cards issued to this competitor
SET cardStateID = 3
WHERE competitorID = <thisCompetitorID>;

INSERT INTO card (competitorID, startDate, endDate, cardStateID)
	VALUES (<thisCompetitorID>, CURDATE(), "2017-08-06", 1);		-- add new VALID card for duration of the competition


-- Additional DML for logging venue access requests
INSERT INTO venueUsage(venueID, cardID, dateAccessed, accessGranted)
VALUES (<thisVenueID>, <thisCardID>, CURDATE(), <accessGrantedYesNo>);

SELECT competitor.fullName, card.ID, venue.venueName, venueUsage.dateAccessed, venueUsage.accessGranted
FROM competitor, card, venue, venueUsage
WHERE venueUsage.venueID = venue.ID
	AND venueUsage.cardID = card.ID
    AND card.competitorID = competitor.ID
    AND venue.ID = <thisVenueID>;


-- UAT Part 4 - Search by card for authorisation to access a venue for a match
SELECT matchAccess.ID, venue.venueName, venue.stadium, matchAccess.matchDate
FROM matchAccess
LEFT JOIN venue
	ON (venue.ID = matchAccess.venueID)
LEFT JOIN team
	ON (team.ID = matchAccess.team1ID OR team.ID = matchAccess.team2ID)
LEFT JOIN competitor
	ON (competitor.teamID = team.ID)
LEFT JOIN card
	ON (card.competitorID = competitor.ID)
WHERE competitor.authorised = TRUE
AND card.cardStateID = 1
AND	card.ID = <cardID>;

-- UAT Part 4 - Display all the competitors who have access to a given venue for a match
SELECT competitor.* 
FROM matchAccess
LEFT JOIN team
	ON (team.ID = matchAccess.team1ID OR team.ID = matchAccess.team2ID)
LEFT JOIN competitor
	ON (competitor.teamID = team.ID)
LEFT JOIN card
	ON (card.competitorID = competitor.ID)
WHERE competitor.Authorised = TRUE
AND card.cardStateID = 1
AND matchAccess.ID = <matchID>;

-- UAT Part 4 - Display all the venues accessible by a given competitor
SELECT venue.*
FROM matchAccess
LEFT JOIN venue
	ON (venue.ID = matchAccess.venueID)
LEFT JOIN team
	ON (team.ID = matchAccess.team1ID OR team.ID = matchAccess.team2ID)
LEFT JOIN competitor
	ON (competitor.teamID = team.ID)
LEFT JOIN card
	ON (card.competitorID = competitor.ID)
WHERE competitor.authorised = TRUE
AND card.cardStateID = 1
AND competitor.ID = <competitorID>;
