CREATE TABLE team (	ID INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
					teamName VARCHAR(128) NOT NULL,
                    nfa VARCHAR(255) NOT NULL,
                    acronym VARCHAR(8),
                    nickname VARCHAR(64),
                    eliminated BOOL NOT NULL DEFAULT FALSE
					);

CREATE TABLE competitorTitle (	ID INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
								title VARCHAR(8) NOT NULL
                                );

CREATE TABLE competitor (	ID INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
							titleID INT NOT NULL,
							fullName VARCHAR(255) NOT NULL,
							role VARCHAR (255) NOT NULL,
                            teamID INT NOT NULL,
                            authorised BOOL NOT NULL DEFAULT TRUE,
                            FOREIGN KEY (titleID) REFERENCES competitorTitle(ID),
                            FOREIGN KEY (teamID) REFERENCES team(ID)
							); 

CREATE TABLE cardState (ID INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
						state VARCHAR(16) NOT NULL
                        );
                        
CREATE TABLE card (	ID INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
					competitorID INT NOT NULL,
                    startDate DATE NOT NULL,
                    endDate DATE NOT NULL,
                    cardStateID INT NOT NULL DEFAULT 1,
					FOREIGN KEY (competitorID) REFERENCES competitor(ID),
                    FOREIGN KEY (cardStateID) REFERENCES cardState(ID)
                    );
                    
CREATE TABLE venue (ID INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
					venueName VARCHAR(255) NOT NULL,
                    stadium VARCHAR(255) NOT NULL
                    );
                    
-- relation matchAccess provides venue permissions for teams 
-- primary key is natural (match number), not auto-incrementing                   
CREATE TABLE matchAccess (	ID INT NOT NULL PRIMARY KEY,
							matchDate DATE NOT NULL,
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
                            dateAccessed DATE NOT NULL,
                            accessGranted BOOL NOT NULL,
                            FOREIGN KEY (cardID) REFERENCES card(ID),
                            FOREIGN KEY (venueID) REFERENCES venue(ID)
                            );

-- relation to store user login accounts (no relationships required)							
CREATE TABLE accounts ( ID INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
						username VARCHAR(255) NOT NULL,
						passwd VARCHAR(255) NOT NULL,
						salt VARCHAR(255) NOT NULL,
						email VARCHAR(255) NOT NULL,
						organisation VARCHAR(255) NOT NULL,
						token VARCHAR(255) DEFAULT NULL
						);