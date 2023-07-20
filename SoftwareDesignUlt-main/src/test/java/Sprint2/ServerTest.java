package Sprint2;

import static org.junit.jupiter.api.Assertions.*;

import java.net.MalformedURLException;
import java.rmi.Naming;
import java.rmi.NotBoundException;
import java.rmi.RemoteException;
import java.rmi.registry.LocateRegistry;
import java.rmi.registry.Registry;

import org.junit.jupiter.api.BeforeEach;
import org.junit.jupiter.api.Test;

import Sprint1.*;

class ServerTest
{
	
	Server MainServer;
	
	Registry registry;

	@BeforeEach
	void setUp() throws Exception
	{
		//step 1 make a registry
		//step 2 make server
		//step 3 register the server with the registry 
		//step 4 use registry to make a proxy
		//step 5 hand proxy to the client
		
		MainServer = new Server();
		registry = LocateRegistry.createRegistry(1099);
		registry.rebind("NewServer", MainServer);
		
		//DB setup
		
		//MainServer.DB = new Database("SH");
		
		User Harry = new User(0, "Worried", "HarryM", "WIC?");
		MainServer.DB.insertUser(Harry);
		User Cybil = new User(1, "Concerned", "CybilB", "BCA!");
		MainServer.DB.insertUser(Cybil);
		User Kaufmann = new User(2, "Annoyed", "MichaelK", "IGOOH");
		MainServer.DB.insertUser(Kaufmann);
		
		Group SH = new Group(0, "SH");
		
		MainServer.DB.Groups.add(SH);
		
		SH.UserIDs.add(1);
		SH.UserIDs.add(2);
		
		Channel street = new Channel(0, "invesigation");
		MainServer.DB.insertChannel(street);
		
		Message m1 = new Message("Where are you now?", 0, 1);
		MainServer.DB.insertMessage(m1);
		Message m2 = new Message("That's not your business", 1, 2);
		MainServer.DB.insertMessage(m2);
		SH.Admins.add(1);
		
		
		
		
		
		
		
	}

	@Test
	void test()
	{
		
		
		try
		{
			
			
			ServerInterface proxy = (ServerInterface) registry.lookup("NewServer");
			Client man = new Client(proxy);
			
			//observed.addObserver(man);
			man.name = "tim";
			//login
			man.insertCred("HarryM", "WIC?");
			man.myID();
			//status
			assertEquals(man.CheckStatus("HarryM"), "Worried");
			man.StatusUpdate("OK");
			assertEquals(man.CheckStatus("HarryM"), "OK");
			assertEquals(man.CheckStatus("CybilB"), "Concerned");
			
			//group joining
			assertEquals(MainServer.DB.getGroup(0).UserIDs.size(), 2);
			man.JoinGroup(0);
			assertEquals(MainServer.DB.getGroup(0).UserIDs.size(), 3);
			assertEquals(MainServer.DB.getGroup(0).UserIDs.get(2), 0);
			
			//message Posting
			Message m3 = new Message("I'm here", 2, 0);
			man.Post(0, 0, m3);
			assertEquals(man.SeeMessage(2), "I'm here");
			
			//Not an Admin
			assertEquals(man.ChangeGroupName("Help", 0), "Not an admin, action unavailable.");
			assertEquals(man.Ban(2, 0), "Not an admin, action unavailable.");
			assertEquals(man.kick(2, 0), "Not an admin, action unavailable.");
			assertEquals(man.Promote(2, 0), "Not an admin, action unavailable.");
			
			//CreateGroup
			man.StartGroup(1, "group1");
			assertEquals(MainServer.DB.getGroup(1).getGroupName(), "group1");
			
			//AdminActions
			man.ChangeGroupName("Group1", 1);
			assertEquals(MainServer.DB.getGroup(1).getGroupName(), "Group1");
			MainServer.DB.getGroup(1).UserIDs.add(2);
			assertEquals(MainServer.DB.getGroup(1).UserIDs.size(), 2);
			man.kick(2, 1);
			assertEquals(MainServer.DB.getGroup(1).UserIDs.size(), 1);
			
			MainServer.DB.getGroup(1).UserIDs.add(2);
			assertEquals(MainServer.DB.getGroup(1).UserIDs.size(), 2);
			man.Ban(2, 1);
			assertEquals(MainServer.DB.getGroup(1).UserIDs.size(), 1);
			
			MainServer.DB.getGroup(1).UserIDs.add(1);
			assertEquals(MainServer.DB.getGroup(1).Admins.size(), 1);
			man.Promote(1, 1);
			assertEquals(MainServer.DB.getGroup(1).Admins.size(), 2);
			
			//Logout
			man.Logout();
			assertEquals(man.myID(), -1);
			
			//different account
			man.insertCred("CybilB", "BCA!");
			assertEquals(man.myID(), 1);
			
			//xml compare
			Database testcase = MainServer.loadFromDisk();
			assertTrue(MainServer.equals(testcase));
			
			
			
		} catch (RemoteException | NotBoundException e)
		{
			// TODO Auto-generated catch block
			e.printStackTrace();
			fail("I failed");
		}
	}
	

}
