CREATE TABLE team (	ID INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
					teamName VARCHAR(128),
                    nfa VARCHAR(255),
                    acronym VARCHAR(8),
                    nickname VARCHAR(64),
                    eliminated BOOL
					);

CREATE TABLE competitorTitle (	ID INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
								title VARCHAR(2)
                                );

CREATE TABLE competitor (	ID INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
							fullName VARCHAR(255),
							role VARCHAR (255),
                            teamID INT NOT NULL,
                            titleID INT,
                            authorised BOOL,
                            FOREIGN KEY (titleID) REFERENCES competitorTitle(ID),
                            FOREIGN KEY (teamID) REFERENCES team(ID)
							); 

CREATE TABLE cardState (ID INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
						state VARCHAR(16)
                        );
                        
CREATE TABLE card (	ID INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
					competitorID INT NOT NULL,
                    startDate DATE,
                    endDate DATE,
                    cardStateID INT NOT NULL,
					FOREIGN KEY (competitorID) REFERENCES competitor(ID),
                    FOREIGN KEY (cardStateID) REFERENCES cardState(ID)
                    );
                    
CREATE TABLE venue (ID INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
					venueName VARCHAR(255),
                    stadium VARCHAR(255)
                    );
                    
-- relation matchAccess provides venue permissions for teams 
-- primary key is natural (match number), not auto-incrementing                   
CREATE TABLE matchAccess (	ID INT NOT NULL PRIMARY KEY,
							matchDate DATE,
                            venueID INT NOT NULL,
							team1ID INT NOT NULL,
							team2ID INT NOT NULL,
							FOREIGN KEY (venueID) REFERENCES venue(ID),
							FOREIGN KEY (team1ID) REFERENCES team(ID),
							FOREIGN KEY (team2ID) REFERENCES team(ID)
                            );
                            
-- relation venueUsage used for logging attempted access of venues                            
CREATE TABLE venueUsage (	ID INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
							cardID INT NOT NULL,
                            venueID INT NOT NULL,
                            dateAccessed DATE,
                            accessGranted BOOL,
                            FOREIGN KEY (cardID) REFERENCES card(ID),
                            FOREIGN KEY (venueID) REFERENCES venue(ID)
                            );

-- relation to store user login accounts (no relationships required)							
CREATE TABLE accounts ( ID INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
						username VARCHAR(255),
						passwd VARCHAR(255),
						salt VARCHAR(255)
						);