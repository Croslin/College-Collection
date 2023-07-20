DROP DATABASE IF EXISTS TherpstonCountyLibrary;
CREATE DATABASE TherpstonCountyLibrary;

USE TherpstonCountyLibrary;

--Use creation scripts to establish tables
SOURCE CreateLibrarianTable.sql;

SOURCE CreatePatronsTable.sql;

SOURCE CreateMediaTable.sql;

SOURCE CreateBooksTable.sql;

SOURCE CreateMoviesTable.sql;

SOURCE CreateMediaReservationsTable.sql;

SOURCE CreateClubsTable.sql;

SOURCE CreateClubMembersTable.sql;

SOURCE CreateClubMediaTable.sql;

--Run Procedure Scripts
SOURCE FunctionFindUser.sql;
SOURCE FunctionFindClub.sql;
SOURCE FunctionFindBook.sql;
SOURCE FunctionFindMovie.sql;
SOURCE ProcedureFindUserPass.sql;
SOURCE FunctionFindUserType.sql;
SOURCE FunctionGetMediaType.sql;
SOURCE FunctionGetDueDate.sql;
SOURCE FunctionGetPatronID.sql;
SOURCE ProcedureInsertBook.sql;
SOURCE ProcedureInsertMovie.sql;