INSERT INTO ###table_prefix###tree (id, parent, name, link, frame, img, sign, order_nr, subtree_identifier, translate, enabled, authorize, protected) VALUES(###id###, "1",       "###name###",     "",                                                                          "",         "admin.gif", "", ###ordernr###,  NULL, "1", "1", "0", "1");
INSERT INTO ###table_prefix###tree (    parent, name, link, frame, img, sign, order_nr, subtree_identifier, translate, enabled, authorize, protected) VALUES(          ###id###,  "show", "../../modules/###name###_###version_main###.###version_sub###.###version_detail###/index.php?command=show_entries", "l4w_main", "",          '', "1",            NULL, "1", "1", "0", "1");


INSERT INTO ###table_prefix###datagrids 
    (mandator_id, name, description, searchButtonCol)
SELECT 1, name, 'created by l4w' AS description, searchButtonCol
FROM ###table_prefix###datagrids
WHERE datagrid_id=10;
			
INSERT INTO ###table_prefix###datagrid_columns 
	(datagrid_id, column_id,column_identifier,column_name, description,
	 width, visible, is_primary, order_nr, searchable)
SELECT ###id###, column_id, column_identifier,column_name, description,
	 width, visible, is_primary, order_nr, searchable
FROM ###table_prefix###datagrid_columns 
WHERE datagrid_id=10
