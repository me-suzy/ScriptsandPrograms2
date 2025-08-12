# phpMyAdmin MySQL-Dump
# version 2.2.5
# http://phpwizard.net/phpMyAdmin/
# http://phpmyadmin.sourceforge.net/ (download page)
#
# Host: localhost
# Generation Time: Jun 08, 2005 at 02:03 AM
# Server version: 3.23.57
# PHP Version: 5.0.0
# Database : `cookdojo_home`
# --------------------------------------------------------

#
# Table structure for table `catrecipe`
#

CREATE TABLE catrecipe (
  catID int(11) NOT NULL auto_increment,
  cat_title varchar(200) NOT NULL default '',
  PRIMARY KEY  (catID)
) TYPE=MyISAM;

#
# Dumping data for table `catrecipe`
#

INSERT INTO catrecipe VALUES (1, 'Barbecue');
INSERT INTO catrecipe VALUES (2, 'Pizza');
INSERT INTO catrecipe VALUES (3, 'Indian');
# --------------------------------------------------------

#
# Table structure for table `recipe`
#

CREATE TABLE recipe (
  recipeID int(11) NOT NULL auto_increment,
  recipe_title varchar(200) NOT NULL default '',
  recipe_ingredients text NOT NULL,
  recipe_method text NOT NULL,
  recipe_note text NOT NULL,
  catID int(11) NOT NULL default '0',
  PRIMARY KEY  (recipeID)
) TYPE=MyISAM;

#
# Dumping data for table `recipe`
#

INSERT INTO recipe VALUES (1, 'Marinated Steak Kabobs', '1 cup Onion, chopped\r\n1/2 cup Vegetable oil\r\n1/2 cup Lemon juice\r\n1/4 cup Soy sauce\r\n1 tablespoon Worcesteshire sauce\r\n1 teaspoon Mustard, prepared\r\n1 pound Sirloin steak, cut in 2&quot;cubes\r\n1 large Green pepper, cut in 1&quot;pieces\r\n2 medium Onions, quartered\r\n2 medium Tomatoes, quartered\r\n1 cup Apple Wood Chips', 'Saute onion in oil; remove from heat. Stir in lemon juice, soy sauce, Worcestershire sauce, and mustard; pour over meat and vegetables. Cover and marinate overnight in refrigerator. Remove meat and vegetables from marinade, reserving marinade. Alternate meat and vegetables on skewers.\r\n\r\nSoak Apple Wood Chips in water for 30 minutes. Prepare fire in grill. When the grill is up up temperature, add wood chips; let them start smoking. Grill kabobs 5 minutes on each side over coals or until desired degree of doneness, brushing frequently with marinade.', '', 1);
INSERT INTO recipe VALUES (2, 'Bourbon Steak', '1-1/2 pounds steak\r\n1 teaspoon. sugar\r\n1/4 cup bourbon\r\n2 tablespoons soy sauce\r\n2 tablespoons water\r\n1 garlic clove, crushed\r\n1 cup Apple Wood Chips', 'Mix all ingredients (except chips) together, place in ziplock bag and marinate steak 4 hours or over night. Soak wood chips in water for 30 minutes, and add to hot coals just before commencing to grill. Grill steak to desired doneness. This recipe is good with any cut of steak you like.', '', 1);
INSERT INTO recipe VALUES (3, 'Baja Pizza Pouch (Calzones)', '1 pound bulk hot pork sausage\r\n1/2 pound lean ground beef\r\n1 can refried beans\r\n1 teaspoon ground cumin\r\n1/4 teaspoon garlic powder\r\n1 package hot roll mix\r\n1 can crushed italian tomatoes\r\n2 cups monterey jack cheese -- shred\r\n1 cup cheddar cheese -- shredded\r\nbutter -- melted\r\n1 cup guacamole', 'Heat large skillet over medium heat until hot. Crumble pork sausage and ground beef into skillet. Cook, stirring to separate meat, until no pink remains. Drain off fat. Add beans, cumin, and garlic powder; heat through. Oil two baking sheets. Preheat oven to 400 F. Prepare hot roll mix. Divide dough into 8 pieces. Form each piece into a ball. On lightly floured surface roll each ball into 7 inch circle. Place on prepared baking sheets. Spread 1/2 c meat mix on 1/2 of each dough circle to within 1/2 inch form edge. Layer each calzone with 2 T crushed tomatoes, 1/4 c\r\n\r\nMonterey Jack cheese and 2 T cheddar. Moisten edges of dough with water and fold over to enclose filling. Press edges firmly together with fork. Brush with melted butter. Cut slit in each calzone to allow steam to escape. Bake 18 to 20 minutes or until golden brown. Serve with guacamole, if desired.', '', 2);
INSERT INTO recipe VALUES (4, 'Cheese Pizza', 'Pizza Crust -- (recipe follows)\r\n1 (8 ounce) can pizza sauce\r\n1 (4 ounce) can sliced mushrooms -- drained OR 1 (4 ounce) can chopped green chiles -- drained\r\n3 cups shredded Mozzarella, Cheddar or Monterey\r\nJack cheese (12 ounces)\r\n1/4 cup grated Parmesan or Romano cheese\r\n\r\nPIZZA CRUST\r\n1 package regular or quick active dry yeast\r\n1 cup warm water (105º to 115º)\r\n2 1/2 cups all-purpose flour*\r\n2 tablespoons olive or vegetable oil\r\n1/2 teaspoon salt\r\nOlive or vegetable oil\r\nCornmeal', 'Prepare Pizza Crust. Spread pizza sauce over partially baked crusts. \r\n\r\nSprinkle with mushrooms and cheeses. Bake pizzas at 425º about 10 minutes, until cheese is melted and pizzas are bubbly.\r\n\r\nMeat Pizza: Cook 1 pound ground beef, bulk Italian sausage or ground turkey, 1 teaspoon Italian seasoning and 2 cloves garlic, finely chopped, in 10-inch medium skillet over medium heat, stirring occasionally, until beef is brown; drain. Sprinkle beef mixture over pizza sauce. Decrease Mozzarella cheese to 2 cups.\r\n\r\nPIZZA CRUST:\r\nDissolve yeast in warm water in medium bowl. Stir in flour, 2 tablespoons oil and the salt. Beat vigorously 20 strokes. Cover and let rest 20 minutes. Move oven rack to lowest position. Heat oven to 425º. Grease 2 cookie sheets or 12-inch pizza pans with oil. Sprinkle with cornmeal.\r\n\r\nDivide dough in half; pat each half onto 11-inch circle on cookie sheet with floured fingers. Prick dough thoroughly with fork. Bake about 10 minutes or until crust just begins to brown.', '', 2);
INSERT INTO recipe VALUES (5, 'CORIANDER FISH (BHARIA MACHLI)', '8 cloves Garlic\r\n3 Hot chilies (optional) (or cayenne)\r\n1&quot; piece Ginger\r\n1 medium bunch Coriander\r\n1 Tbsp Coriander seeds\r\n1 tsp Brown sugar\r\n1 tsp Turmeric\r\n1/2 tsp Black mustard\r\n1/2 tsp Fenugreek seeds\r\n1 Tbsp Salt\r\n1/2 cup Lemon juice\r\n1/2 cup Vegetable oil\r\n2 cup Chopped onion\r\n1 cup Chopped tomato\r\n1/2 tsp Garam Masala', 'Preheat oven to 400 deg F. Wash and pat fish dry. Sprinkle 1 t salt inside and set aside. Blend garlic, chili, ginger, 1/2 the coriander, coriander seeds, brown sugar, turmeric, mustard seeds, fenugreek seeds, salt and lemon juice until it all becomes a smooth paste (Add some water if needed).\r\n\r\nFry onions until they are soft and golden brown. Add the blended Masala and cook until most of the liquid is gone, and it starts to leave the sides of the pan. Add the tomatoes and Garam Masala. Fry for 2 minutes more and remove.\r\n\r\nCoat one side of fish, stuff 1 1/2 cups inside. Close opening, spread the rest of the Masala over it. Cover tightly and bake for about 25 minutes. Grill for 1 -2 minutes in the broiler, and sprinkle on the remaining coriander. Serve.', '', 3);
INSERT INTO recipe VALUES (6, 'PRAWN (SHRIMP) CURRY', '1/2 kg = 1.1 lb. Prawns\r\n2 Onions diced into small pieces\r\n1/4&quot; Cinnamon stick\r\n1/4 tsp Chili powder\r\n1/2 tsp Dhania powder\r\n1/4 tsp Garlic powder\r\n1/2 tsp ginger powder\r\n1 bunch Fresh coriander\r\n1 tsp Salt\r\n1/4 tsp Turmeric powder\r\n1 tblsp Oil', 'Clean the prawns and squeeze out the water. Add chili, dhania, garlic, ginger, turmeric powder, salt and mix well. Boil prawns on low heat. Add 1 teaspoon of oil to the boiling prawns.\r\n\r\nWhen water evaporates and the prawns are dry remove from the stove. Heat the oil and put in the cinnamon. Add prawns and fry for 2 minutes. Add onions and fry until they turn brown. Sprinkle on coriander leaves, remove from the heat and serve.', '', 3);

