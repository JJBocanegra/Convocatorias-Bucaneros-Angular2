BEGIN TRANSACTION;
CREATE TABLE "Team" (
	`teamId`	INTEGER PRIMARY KEY AUTOINCREMENT,
	`name`	TEXT NOT NULL,
	`localization`	TEXT,
	`urlLocalization`	TEXT
);
CREATE TABLE "SeasonPlayer" (
	`seasonId`	INTEGER,
	`playerId`	INTEGER,
	`incorporationDate`	INTEGER DEFAULT 0,
	PRIMARY KEY(seasonId,playerId)
);
CREATE TABLE "Season" (
	`seasonId`	INTEGER PRIMARY KEY AUTOINCREMENT,
	`beginDate`	TEXT NOT NULL UNIQUE,
	`endDate`	TEXT NOT NULL UNIQUE
);
CREATE TABLE "Player" (
	`playerId`	INTEGER PRIMARY KEY AUTOINCREMENT,
	`name`	TEXT NOT NULL,
	`surname`	TEXT,
	`nickname`	TEXT UNIQUE,
	`birthDate`	TEXT,
	`hasImage`	INTEGER DEFAULT 0
);
CREATE TABLE "MatchPlayer" (
	`matchId`	INTEGER,
	`playerId`	INTEGER,
	`tries`	INTEGER DEFAULT 0,
	`conversions`	INTEGER DEFAULT 0,
	`failedConversions`	INTEGER DEFAULT 0,
	`dropGoals`	INTEGER DEFAULT 0,
	`yellowCards`	INTEGER DEFAULT 0,
	`redCards`	INTEGER DEFAULT 0,
	PRIMARY KEY(matchId,playerId)
);
CREATE TABLE "Match" (
	`matchId`	INTEGER PRIMARY KEY AUTOINCREMENT,
	`dateTime`	TEXT NOT NULL,
	`localTeamId`	INTEGER NOT NULL,
	`visitorTeamId`	INTEGER NOT NULL,
	`state`	TEXT,
	`localScore`	INTEGER,
	`visitorScore`	INTEGER
);
CREATE TABLE `InjuredPlayer` (
	`matchId`	INTEGER,
	`playerId`	INTEGER,
	PRIMARY KEY(matchId,playerId)
);
CREATE VIEW GetNotConfirmedPlayersFromLastMatch AS
SELECT p.*, (p.name || ' ' || p.surname) as fullName 
FROM Player p 
WHERE p.playerId NOT IN (SELECT playerId FROM MatchPlayer WHERE matchId = (SELECT MAX(matchId) FROM Match)) 
AND p.playerId NOT IN (SELECT playerId FROM InjuredPlayer WHERE matchId = (SELECT MAX(matchId) FROM Match)) 
AND p.playerId IN (SELECT playerId FROM GetCurrentSeasonPlayers);
CREATE VIEW GetCurrentSeasonPlayers AS
SELECT p.* From Player p, SeasonPlayer sp, GetCurrentSeason s 
WHERE sp.seasonId = s.seasonId
AND p.playerId = sp.playerId;
CREATE VIEW GetCurrentSeason AS
SELECT *, strftime('%Y/%m/%d', 'now', 'localtime') as today
FROM Season
WHERE today > beginDate
AND today < endDate;
CREATE VIEW GetAllNotConfirmedPlayers AS
SELECT DISTINCT m.matchId, p.playerId, (p.name || ' ' || p.surname) as fullName, p.nickname, m.dateTime, localTeam.name as local, visitorTeam.name as visitor
FROM Player p, Match m, SeasonPlayer sp, Season s, Team localTeam, Team visitorTeam
WHERE p.playerId NOT IN (SELECT playerId FROM GetAllMatchPlayers WHERE matchId = m.matchId)
AND p.playerId NOT IN (SELECT playerId FROM GetAllInjuredPlayers WHERE matchId = m.matchId)
AND p.playerId = sp.playerId
AND s.seasonId = sp.seasonId
AND m.dateTime > s.beginDate
AND m.dateTime < s.endDate
AND m.dateTime > sp.incorporationDate
AND localTeam.teamId = m.localTeamId
AND visitorTeam.teamId = m.visitorTeamId
ORDER BY m.dateTime DESC;
CREATE VIEW GetAllMatchPlayers AS
SELECT mp.matchId, p.playerId, (p.name || ' ' || p.surname) as fullName, p.nickname, m.dateTime, localTeam.name as local, visitorTeam.name as visitor
FROM Player p, MatchPlayer mp, Match m, Team localTeam, Team visitorTeam
WHERE p.playerId = mp.playerId
AND m.matchId = mp.matchId
AND localTeam.teamId = m.localTeamId
AND visitorTeam.teamId = m.visitorTeamId
ORDER BY m.dateTime DESC, p.playerId;
CREATE VIEW GetAllInjuredPlayers AS
SELECT ip.matchId, p.playerId, (p.name || ' ' || p.surname) as fullName, p.nickname, m.dateTime, localTeam.name as local, visitorTeam.name as visitor
FROM Player p, InjuredPlayer ip, Match m, Team localTeam, Team visitorTeam
WHERE p.playerId = ip.playerId
AND ip.matchId = m.matchId
AND localTeam.teamId = m.localTeamId
AND visitorTeam.teamId = m.visitorTeamId
ORDER BY m.dateTime DESC, p.playerId;
COMMIT;
