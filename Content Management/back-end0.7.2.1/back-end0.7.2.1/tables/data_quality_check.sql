-- Quick Back-End data-quality checker
-- All the queries below should return an empty resultset - if there are records, the data is inconsistent
-- Currently, the focus is on the core tables - users, blocks, articles and sections
-- $Id: data_quality_check.sql,v 1.2 2005/05/27 14:47:16 iclysdal Exp $

--  AUTHOR/GROUP

-- Bad pivot table values
SELECT l.* FROM psl_author_group_lut l
LEFT OUTER JOIN psl_author c USING(author_id)
WHERE c.author_id IS NULL;

SELECT l.* FROM psl_author_group_lut l
LEFT OUTER JOIN psl_group c USING(group_id)
WHERE l.group_id IS NULL;

-- Authors with no valid groups
SELECT l.* FROM psl_author c
LEFT OUTER JOIN psl_author_group_lut l USING(author_id)
WHERE l.author_id IS NULL;

-- Groups with invalid childgroups
SELECT l.* FROM psl_group_group_lut l
LEFT OUTER JOIN psl_group c ON l.group_id=c.group_id
WHERE c.group_id IS NULL;

SELECT l.* FROM psl_group_group_lut l
LEFT OUTER JOIN psl_group c ON l.childgroup_id=c.group_id
WHERE c.group_id IS NULL;

-- Groups with invalid permissions
SELECT l.* FROM psl_group_permission_lut l
LEFT OUTER JOIN psl_group c ON l.group_id=c.group_id
WHERE c.group_id IS NULL;

SELECT l.* FROM psl_group_permission_lut l
LEFT OUTER JOIN psl_permission c ON l.permission_id =c.permission_id
WHERE c.permission_id IS NULL;

-- ARTICLE/SECTION

-- Missing Text records

SELECT t.* FROM be_articleText t
LEFT OUTER JOIN be_articles c USING(articleID)
WHERE c.articleID IS NULL;

SELECT t.* FROM be_sectionText t
LEFT OUTER JOIN be_sections c USING(sectionID)
WHERE c.sectionID IS NULL;

-- Orphan Text records

SELECT c.* FROM be_articles c
LEFT OUTER JOIN be_articleText t USING(articleID)
WHERE t.articleID IS NULL;

SELECT c.* FROM be_sections c
LEFT OUTER JOIN be_sectionText t USING(sectionID)
WHERE t.sectionID IS NULL;

-- Bad pivot table values

SELECT l.* FROM be_article2section l
LEFT OUTER JOIN be_sections c USING(sectionID)
WHERE c.sectionID IS NULL;

SELECT l.* FROM be_article2section l
LEFT OUTER JOIN be_articles c USING(articleID)
WHERE c.articleID IS NULL;

-- BLOCKS

-- Orphan Text records
SELECT t.* from psl_blockText t
LEFT OUTER JOIN psl_block c ON t.id=c.id
WHERE c.id IS NULL;
-- OK

-- Missing Text records
SELECT c.* from psl_block c
LEFT OUTER JOIN psl_blockText t ON t.id=c.id
WHERE t.id IS NULL;
-- 1 ROW

-- Bad pivot table values
SELECT l.* FROM psl_section_block_lut l
LEFT OUTER JOIN psl_block c ON c.id=l.block_id
WHERE c.id IS NULL;
-- ==> 55 ROWS

SELECT l.* FROM psl_section_block_lut l
LEFT OUTER JOIN be_sections c ON c.sectionID=l.section_id
WHERE c.sectionID IS NULL;
-- ==> 74 ROWS

-- Bad block types
SELECT c.* from psl_block c
LEFT OUTER JOIN psl_block_type t  ON t.id=c.type
WHERE t.id IS NULL;
