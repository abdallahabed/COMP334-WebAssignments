Drop DATABASE IF EXISTS travel_company;
CREATE DATABASE travel_company;
USE travel_company;

DROP TABLE IF EXISTS trips;
CREATE TABLE trips(
    trip_id INT PRIMARY KEY AUTO_INCREMENT, 
    trip_name VARCHAR(200) NOT NULL,
    destination VARCHAR(200) NOT NULL,
    duration_days INT NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    start_date DATE NOT NULL,
    end_date DATE NOT NULL,
    available_seats INT NOT NULL,
    description TEXT,
    itinerary TEXT,
    inclusions TEXT,
    exclusions TEXT,
    requirements TEXT,
    image_url VARCHAR(255)

);

DROP TABLE IF EXISTS bookings;

CREATE TABLE bookings (
booking_id INT PRIMARY KEY AUTO_INCREMENT,
trip_id INT NOT NULL,
customer_name VARCHAR(100) NOT NULL,
customer_email VARCHAR(100) NOT NULL,
customer_phone VARCHAR(20) NOT NULL,
num_travelers INT NOT NULL,
total_amount DECIMAL(10,2) NOT NULL,
payment_method VARCHAR(50),
card_number VARCHAR(20),
booking_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
special_requests TEXT,
FOREIGN KEY (trip_id) REFERENCES trips(trip_id) ON DELETE CASCADE
);



INSERT INTO trips (trip_name,destination,duration_days,price,start_date,end_date,available_seats,description,itinerary,inclusions,exclusions,requirements,image_url) VALUES
('Jerusalem Highlights','Jerusalem',3,450.00,'2026-01-10','2026-01-12',20,'Discover historic Jerusalem and key religious sites.','Day 1: Old City walking tour|Day 2: Al-Aqsa Mosque & Dome of the Rock|Day 3: Markets and departure','2 nights hotel|Daily breakfast|Professional guide|Transport|Entrance fees','International flights|Lunches|Personal expenses|Tips','Valid ID|Comfortable walking shoes','images/jerusalem.jpg'),

('Bethlehem & Churches','Bethlehem',2,320.00,'2026-02-14','2026-02-15',15,'Visit the Church of the Nativity and local cultural sites.','Day 1: Nativity Church & Manger Square|Day 2: Local markets and departure','1 night hotel|Breakfast|Guide|Transport','Flights|Lunches|Optional tours','Valid ID|Respectful clothing','images/bethlehem.jpg'),

('Hebron Historical Tour','Hebron',1,180.00,'2026-03-05','2026-03-05',30,'Walking tour of Hebron historic areas and markets.','Day 1: Cave of the Patriarchs|Local souk visit and guided walk','Local guide|Transport|Entrance fees','Flights|Meals|Tips','Valid ID|Moderate fitness','images/hebron.jpg'),

('Jericho & Dead Sea','Jericho',2,350.00,'2026-04-01','2026-04-02',18,'Relax at the Dead Sea and explore Jericho ruins.','Day 1: Jericho archaeological sites|Day 2: Dead Sea float and spa time','1 night hotel|Breakfasts|Guide|Transport|Dead Sea entry','Flights|Lunches|Spa extras','Swimwear|Sunscreen|Valid ID','images/jericho.jpg'),

('Nablus Cultural Experience','Nablus',2,400.00,'2026-05-10','2026-05-11',12,'Explore Nablus old town, soap factories, and cuisine.','Day 1: Old City tour & soap factory|Day 2: Local crafts and departure','1 night hotel|Breakfast|Guide|Transport','Flights|Lunches|Personal expenses','Comfortable shoes|Valid ID','images/nablus.jpg'),

('Ramallah Modern Culture','Ramallah',3,600.00,'2026-06-05','2026-06-07',10,'Museums, cultural centers and modern city life.','Day 1: Museums and cultural centers|Day 2: Local NGOs & culinary tour|Day 3: Markets and departure','2 nights hotel|Daily breakfast|Guided tours|Transport','Flights|Lunches|Optional events','Valid passport|Respectful attire','images/ramallah.jpg'),

('West Bank Bestsellers - 7 Days','Multiple',7,1200.00,'2026-07-01','2026-07-07',8,'Seven-day comprehensive tour across main sites.','Day 1: Jerusalem|Day 2: Bethlehem|Day 3: Hebron|Day 4: Jericho|Day 5: Nablus|Day 6: Ramallah|Day 7: Departure','6 nights hotel|Daily breakfast & dinner|Guide|Transport|Entrance fees','International flights|Optional activities|Personal expenses','Valid passport|Travel insurance|Comfortable shoes','images/7days.jpg'),

('Pilgrimage & Heritage - 5 Days','Jerusalem,Bethlehem',5,900.00,'2026-09-10','2026-09-14',14,'Religious and heritage sites tour for pilgrims.','Day 1: Arrival and welcome|Day 2: Jerusalem religious sites|Day 3: Bethlehem visits|Day 4: Hebron|Day 5: Departure','4 nights hotel|Meals as specified|Guide|Transport|Entrance fees','Flights|Lunches|Personal expenses','Valid passport|Respectful clothing','images/pilgrimage.jpg'),

('Adventure & Hikes - 4 Days','Jericho,Nablus',4,700.00,'2026-10-05','2026-10-08',20,'Hiking routes and local nature walks.','Day 1: Jericho hikes|Day 2: Wadi walks|Day 3: Nablus exploration|Day 4: Departure','3 nights hotel|Breakfasts|Guide|Transport|Safety gear provided','Flights|Lunches|Personal expenses','Good fitness level|Hiking shoes|Valid ID','images/adventure.jpg'),

('Private Custom Tour - 14 Days','Multiple',14,2000.00,'2026-11-01','2026-11-14',5,'Private customizable tour tailored to the group.','Day 1: Tailored activities|Day 2: Tailored activities|...|Day 14: Departure','13 nights accommodation|All transfers|Private guide|Custom itinerary','Flights|Personal expenses|Optional extras','Passport|Insurance|Any required visas','images/private.jpg');





