INSERT INTO camp_groups VALUES (22, 1, 57);
INSERT INTO camp_groups VALUES (21, 1, 56);
INSERT INTO camp_groups VALUES (20, 1, 21);
INSERT INTO camp_groups VALUES (19, 1, 17);
INSERT INTO camp_groups VALUES (18, 1, 20);
INSERT INTO camp_groups VALUES (6, 2, 16);
INSERT INTO camp_groups VALUES (7, 2, 17);
INSERT INTO camp_groups VALUES (8, 2, 22);
INSERT INTO camp_groups VALUES (9, 2, 21);
INSERT INTO camp_groups VALUES (17, 3, 23);
INSERT INTO camp_groups VALUES (16, 3, 22);
INSERT INTO camp_groups VALUES (15, 3, 16);
INSERT INTO camp_groups VALUES (14, 3, 1);
INSERT INTO camp_groups VALUES (23, 4, 1);
INSERT INTO camp_groups VALUES (24, 4, 15);
INSERT INTO camp_groups VALUES (25, 4, 16);
INSERT INTO camp_groups VALUES (26, 4, 2);
INSERT INTO camp_groups VALUES (27, 4, 17);
INSERT INTO camp_groups VALUES (28, 4, 18);
INSERT INTO camp_groups VALUES (29, 4, 19);
INSERT INTO camp_groups VALUES (30, 4, 20);
INSERT INTO camp_groups VALUES (31, 4, 3);
INSERT INTO camp_groups VALUES (32, 4, 21);
INSERT INTO camp_groups VALUES (33, 4, 22);
INSERT INTO camp_groups VALUES (34, 4, 23);
INSERT INTO camp_groups VALUES (35, 4, 24);

INSERT INTO campaigns VALUES (1, 1, 'http://www.projectsagent.com', 20, 'projects agent', 'Projects Agent', 1, 1029757652);
INSERT INTO campaigns VALUES (2, 2, 'http://www.ettica.com', 22, 'design & dev', 'Ettica Interactive Website', 1, 1029837138);
INSERT INTO campaigns VALUES (3, 1, 'http://ntm3k.projectsagent.com', 1, 'sintetik', 'ntm3k', 1, 1031322945);
INSERT INTO campaigns VALUES (4, 3, 'http://www.google.com', 17, 'test', 'Test Campaign', 1, 1031836747);

INSERT INTO logs VALUES (1, 0, 1029836960);
INSERT INTO logs VALUES (2, 1, 1031836867);
INSERT INTO logs VALUES (3, 2, 1031836871);

INSERT INTO members VALUES (1, 'ntm3k', 'nitema', 'Teodor-Marian', 'Nica', 'ntm3k@rol.ro', 'Buzau', 'International', 'USA', '5100', '.', '.', '', 1, 1029446335);
INSERT INTO members VALUES (2, 'ettica', 'sintetik', 'Ettica', 'Interactive', 'ntm@projectsagent.com', 'Bucuresti', 'International', 'USA', '5100', '.', '.', '', 1, 1029837026);
INSERT INTO members VALUES (3, 'test', 'test', 'Test', 'Member', 'test@test.it', 'City', 'International', 'Other-Not Shown', 'Zip', 'Phone', 'Fax', '', 1, 1031836539);

INSERT INTO members_credits VALUES (1, 1, '16.00');
INSERT INTO members_credits VALUES (2, 2, '20.00');

INSERT INTO payment_modes VALUES (1, '10', '100', 'Paypal 10*', 'http://www.paypal.com?1');
INSERT INTO payment_modes VALUES (2, '20', '300', 'Paypal 20*', 'http://www.paypal.com?2');

INSERT INTO prev VALUES (1, 1, '125');
INSERT INTO prev VALUES (2, 3, '40');
INSERT INTO prev VALUES (3, 2, '80');
INSERT INTO prev VALUES (4, 4, '500');
