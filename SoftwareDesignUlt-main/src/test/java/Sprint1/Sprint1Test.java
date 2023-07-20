package Sprint1;

import static org.junit.jupiter.api.Assertions.*;

import org.junit.jupiter.api.BeforeEach;
import org.junit.jupiter.api.Test;

class Sprint1Test
{
	
	Database RCPD;
	
	User Albert;
	User Barry;
	User Chris;
	User Jill;
	User Rebecca;
	
	Message M1;
	
	Group STARS;
	
	Channel Bravo;
	

	@BeforeEach
	void setUp() throws Exception
	{
		
		RCPD = new Database("PD");
		
		Albert = new User(0, "Splendid", "AlbertW", "AW1");
		RCPD.insertUser(Albert);
		Barry = new User(1, "Amazing", "BarryB", "BB1");
		RCPD.insertUser(Barry);
		Chris = new User(2, "Unban me please", "ChrisR", "CR1");
		RCPD.insertUser(Chris);
		Jill = new User(3, "ok", "JillV", "JV1");
		RCPD.insertUser(Jill);
		Rebecca = new User(3, "nervous", "RebeccaCh", "RC2");
		RCPD.insertUser(Rebecca);
		
		M1 = new Message("Whiskers", 0, 2);
		RCPD.insertMessage(M1);
		
		Bravo = new Channel(0, "Bravo Team");
		RCPD.insertChannel(Bravo);
		
		STARS = new Group(0, "Stars");
		RCPD.insertGroup(STARS);
		
		
		
		
		
		
	}

	@Test
	void test()
	{
		
		
		//Database testing
		
		assertEquals(Albert.UserID, 0);
		assertEquals(Barry.Status, "Amazing");
		assertEquals(Rebecca.username, "RebeccaCh");
		assertEquals(Jill.password, "JV1");
		
		//User p;
		//p = RCPD.getUser(0);
		assertEquals(RCPD.getUser(2), Chris);
		assertEquals(RCPD.getMessage(0), M1);
		assertEquals(RCPD.getChannel(0), Bravo);
		assertEquals(RCPD.getGroup(0), STARS);
		
		
		//User testing
		
		assertEquals(Rebecca.getPassword(), "RC2");
		Rebecca.setPassword("RC1");
		assertEquals(Rebecca.getPassword(), "RC1");
		
		assertEquals(Rebecca.getUserID(), Jill.getUserID());
		Rebecca.setUserID(4);
		assertEquals(Rebecca.getUserID(), 4);
		
		assertEquals(Rebecca.getUsername(), "RebeccaCh");
		Rebecca.setUsername("RebeccaC");
		assertEquals(Rebecca.getUsername(), "RebeccaC");
		
		assertEquals(Rebecca.getStatus(), "nervous");
		Rebecca.setStatus("excited");
		assertEquals(Rebecca.getStatus(), "excited");
		
		
		//Group testing
		
		STARS.setAdminUser(0);
		assertEquals(STARS.Admins.get(0), 0);
		
		assertEquals(STARS.GroupName, "Stars");
		STARS.editGroupName("S.T.A.R.S.");
		assertEquals(STARS.GroupName, "S.T.A.R.S.");
		
		STARS.setAdminUser(0);
		assertEquals(STARS.Admins.get(0), 0);
		
		STARS.UserIDs.add(2);
		STARS.UserIDs.add(0);
		assertEquals(STARS.UserIDs.get(0),2);
		
		STARS.kickUser(2);
		assertEquals(STARS.UserIDs.get(0), 0);
		
		STARS.UserIDs.add(2);
		STARS.UserIDs.add(1);
		assertEquals(STARS.UserIDs.get(1), 2);
		STARS.banUser(2);
		assertEquals(STARS.UserIDs.get(1), 1);
		assertEquals(STARS.BannedUsers.get(0), 2);
		
		
		//Channel testing
		
		Bravo.MessageIDs.add(0);
		Bravo.MessageIDs.add(3);
		assertEquals(Bravo.MessageIDs.get(0), 0);
		Bravo.deleteMessage(0);
		assertEquals(Bravo.MessageIDs.get(0), 3);
		Bravo.postMessage(0);
		assertEquals(Bravo.MessageIDs.get(1), 0);
		
		
		
		
		
	}

}
