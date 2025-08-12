/*******************************************************************************
**                                                                            **
** ASP.NET Developers Guide, Chapter 7                                        **
** This is the script to build the Address book sample                        **
** database.                                                                  **
**                                                                            **
** Replace <your path here> with the path to where                            **
** you would like your files to be.                                           **
**                                                                            **
** The script will create a login called Chapter7 with a password of chapter7 **
**                                                                            **
*******************************************************************************/


IF EXISTS (SELECT name FROM master.dbo.sysdatabases WHERE name = N'Chapter7')
	DROP DATABASE [Chapter7]
GO

CREATE DATABASE [Chapter7]  ON (NAME = N'Chapter7_Data'
				, FILENAME = N'<your path here>\Chapter7_Data.MDF' 
				, SIZE = 1
				, FILEGROWTH = 10%) 
			LOG ON (NAME = N'Chapter7_Log'
				, FILENAME = '<your path here>\Chapter7_Log.LDF' 
				, SIZE = 1
				, FILEGROWTH = 10%)
 COLLATE SQL_Latin1_General_CP1_CI_AS
GO

exec sp_dboption N'Chapter7', N'autoclose', N'false'
GO

exec sp_dboption N'Chapter7', N'bulkcopy', N'false'
GO

exec sp_dboption N'Chapter7', N'trunc. log', N'false'
GO

exec sp_dboption N'Chapter7', N'torn page detection', N'true'
GO

exec sp_dboption N'Chapter7', N'read only', N'false'
GO

exec sp_dboption N'Chapter7', N'dbo use', N'false'
GO

exec sp_dboption N'Chapter7', N'single', N'false'
GO

exec sp_dboption N'Chapter7', N'autoshrink', N'false'
GO

exec sp_dboption N'Chapter7', N'ANSI null default', N'false'
GO

exec sp_dboption N'Chapter7', N'recursive triggers', N'false'
GO

exec sp_dboption N'Chapter7', N'ANSI nulls', N'false'
GO

exec sp_dboption N'Chapter7', N'concat null yields null', N'false'
GO

exec sp_dboption N'Chapter7', N'cursor close on commit', N'false'
GO

exec sp_dboption N'Chapter7', N'default to local cursor', N'false'
GO

exec sp_dboption N'Chapter7', N'quoted identifier', N'false'
GO

exec sp_dboption N'Chapter7', N'ANSI warnings', N'false'
GO

exec sp_dboption N'Chapter7', N'auto create statistics', N'true'
GO

exec sp_dboption N'Chapter7', N'auto update statistics', N'true'
GO

use [Chapter7]
GO

if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].[tblAddress]') and OBJECTPROPERTY(id, N'IsUserTable') = 1)
drop table [dbo].[tblAddress]
GO

if not exists (select * from master.dbo.syslogins where loginname = N'Chapter7')
BEGIN
	declare @logindb nvarchar(132), @loginlang nvarchar(132) select @logindb = N'Chapter7', @loginlang = N'us_english'
	if @logindb is null or not exists (select * from master.dbo.sysdatabases where name = @logindb)
		select @logindb = N'master'
	if @loginlang is null or (not exists (select * from master.dbo.syslanguages where name = @loginlang) and @loginlang <> N'us_english')
		select @loginlang = @@language
	exec sp_addlogin N'Chapter7', 'chapter7', @logindb, @loginlang
END
GO

if not exists (select * from dbo.sysusers where name = N'Chapter7' and uid < 16382)
	EXEC sp_grantdbaccess N'Chapter7', N'Chapter7'
GO

exec sp_addrolemember N'db_datareader', N'Chapter7'
GO

exec sp_addrolemember N'db_datawriter', N'Chapter7'
GO

CREATE TABLE [dbo].[tblAddress] (
	[AdrsID] [int] IDENTITY (0, 1) NOT NULL ,
	[FName] [varchar] (50) COLLATE SQL_Latin1_General_CP1_CI_AS NULL ,
	[LName] [varchar] (50) COLLATE SQL_Latin1_General_CP1_CI_AS NULL ,
	[Phone] [char] (15) COLLATE SQL_Latin1_General_CP1_CI_AS NULL ,
	[EMail] [varchar] (255) COLLATE SQL_Latin1_General_CP1_CI_AS NULL ,
	[WebPage] [varchar] (255) COLLATE SQL_Latin1_General_CP1_CI_AS NULL ,
	[Age] [tinyint] NULL ,
	[Comments] [varchar] (2000) COLLATE SQL_Latin1_General_CP1_CI_AS NULL 
) ON [PRIMARY]
GO

ALTER TABLE [dbo].[tblAddress] WITH NOCHECK ADD 
	CONSTRAINT [PK_tblAddress] PRIMARY KEY  CLUSTERED 
	(
		[AdrsID]
	)  ON [PRIMARY] 
GO

if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].[usp_tblAddress_del]') and OBJECTPROPERTY(id, N'IsProcedure') = 1)
drop procedure [dbo].[usp_tblAddress_del]
GO

if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].[usp_tblAddress_ins]') and OBJECTPROPERTY(id, N'IsProcedure') = 1)
drop procedure [dbo].[usp_tblAddress_ins]
GO

if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].[usp_tblAddress_sel]') and OBJECTPROPERTY(id, N'IsProcedure') = 1)
drop procedure [dbo].[usp_tblAddress_sel]
GO

if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].[usp_tblAddress_sel_ByID]') and OBJECTPROPERTY(id, N'IsProcedure') = 1)
drop procedure [dbo].[usp_tblAddress_sel_ByID]
GO

if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].[usp_tblAddress_upd]') and OBJECTPROPERTY(id, N'IsProcedure') = 1)
drop procedure [dbo].[usp_tblAddress_upd]
GO

SET QUOTED_IDENTIFIER ON 
GO
SET ANSI_NULLS ON 
GO


create proc usp_tblAddress_del(
	@AdrsID INT
)
AS

DELETE FROM [dbo].[tblAddress]
WHERE [AdrsID] = @AdrsID


GO
SET QUOTED_IDENTIFIER OFF 
GO
SET ANSI_NULLS ON 
GO

GRANT  EXECUTE  ON [dbo].[usp_tblAddress_del]  TO [Chapter7]
GO

SET QUOTED_IDENTIFIER OFF 
GO
SET ANSI_NULLS ON 
GO


CREATE proc usp_tblAddress_ins(
  @AdrsID  INT = NULL OUTPUT
, @FName  varchar(50)
, @LName   varchar(50)
, @Phone   char(15)
, @EMail   varchar(255)
, @WebPage varchar(255)
, @Age     tinyint
, @Comments varchar(2000)
)
AS

INSERT INTO [Chapter7].[dbo].[tblAddress]([FName]
	, [LName]
	, [Phone]
	, [EMail]
	, [WebPage]
	, [Age]
	, [Comments])
VALUES(  @FName
	, @LName
	, @Phone
	, @EMail
	, @WebPage
	, @Age
	, @Comments
)

SET @AdrsID = @@IDENTITY
GO
SET QUOTED_IDENTIFIER OFF 
GO
SET ANSI_NULLS ON 
GO

GRANT  EXECUTE  ON [dbo].[usp_tblAddress_ins]  TO [Chapter7]
GO

SET QUOTED_IDENTIFIER ON 
GO
SET ANSI_NULLS ON 
GO

create proc usp_tblAddress_sel
AS
SELECT [AdrsID]
, [FName]
, [LName]
, [Phone]
, [EMail]
, [WebPage]
, [Age]
, [Comments] 
FROM [dbo].[tblAddress]
ORDER BY [LName], [FName]

GO
SET QUOTED_IDENTIFIER OFF 
GO
SET ANSI_NULLS ON 
GO

GRANT  EXECUTE  ON [dbo].[usp_tblAddress_sel]  TO [Chapter7]
GO

SET QUOTED_IDENTIFIER ON 
GO
SET ANSI_NULLS ON 
GO


create proc usp_tblAddress_sel_ByID(
	@AdrsID INT
)
AS 

SELECT [AdrsID]
, [FName]
, [LName]
, [Phone]
, [EMail]
, [WebPage]
, [Age]
, [Comments] 
FROM [dbo].[tblAddress]
WHERE [AdrsID] = @AdrsID


GO
SET QUOTED_IDENTIFIER OFF 
GO
SET ANSI_NULLS ON 
GO

GRANT  EXECUTE  ON [dbo].[usp_tblAddress_sel_ByID]  TO [Chapter7]
GO

SET QUOTED_IDENTIFIER ON 
GO
SET ANSI_NULLS ON 
GO



create proc usp_tblAddress_upd(
  @AdrsID  INT
, @FName  varchar(50)
, @LName   varchar(50)
, @Phone   char(15)
, @EMail   varchar(255)
, @WebPage varchar(255)
, @Age     tinyint
, @Comments varchar(2000)
)
AS

UPDATE [dbo].[tblAddress]
SET [FName]= @FName
, [LName]= @LName
, [Phone]= @Phone
, [EMail]= @EMail
, [WebPage]= @WebPage
, [Age]= @Age
, [Comments]= @Comments
WHERE [AdrsID]=@AdrsID


GO
SET QUOTED_IDENTIFIER OFF 
GO
SET ANSI_NULLS ON 
GO

GRANT  EXECUTE  ON [dbo].[usp_tblAddress_upd]  TO [Chapter7]
GO

