
INSERT INTO ###scheme###.###table_prefix###tree 
    (parent, name, link, frame, img, sign, order_nr, enabled, authorize, protected, visible_for_guest) 
VALUES("1", "###name###",  "", "", "notes.gif", "", ###ordernr###, "1", "1", "0", "1");

INSERT INTO ###scheme###.###table_prefix###tree 
    (parent, name, link, frame, img, sign, order_nr, enabled, authorize, protected, visible_for_guest) 
VALUES(###id###,  "new ###name###", "../../modules/###name###_###version###/index.php?command=add_contact_view", "l4w_main", "",          '', "1",          "1", "1", "0", "1");

INSERT INTO ###scheme###.###table_prefix###tree 
    (parent, name, link, frame, img, sign, order_nr, enabled, authorize, protected, visible_for_guest) 
VALUES(###id###,  "browse",         "../../modules/###name###_###version###/index.php?command=show_entries",     "l4w_main", "",          '', "2",          "1", "1", "0", "1");
