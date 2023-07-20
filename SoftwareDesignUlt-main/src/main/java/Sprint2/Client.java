package Sprint2;

import java.io.Serializable;
import java.net.MalformedURLException;
import java.rmi.Naming;
import java.rmi.NotBoundException;
import java.rmi.RemoteException;
import java.rmi.server.UnicastRemoteObject;
import java.util.ArrayList;

import Sprint1.Message;
import Sprint1.User;

public class Client extends UnicastRemoteObject implements RMIObserver, Serializable
{
	
	ServerInterface Serv;
	int UID;
	User CU;
	//RMIObserver OB;
	
	

	public Client(ServerInterface S) throws RemoteException
	{
		Serv = S;
		UID = -1;
		CU = null;
	}

	private static final long serialVersionUID = -2323354517016389390L;
	
	String name = "cl";
	

	@Override
	public void notifyFinished()
	{
		System.out.println("There has been a change!");

	}
	
	public void insertCred(String UN, String PW) throws RemoteException
	{
		UID = Serv.CheckCred(UN, PW);
		CU = Serv.ReturnUser(UID);
		Serv.addObserver(this);
	}
	
	public int myID() throws RemoteException
	{
		System.out.println("Your ID is "+ UID);
		return UID;
	}
	
	public void Logout() throws RemoteException
	{
		UID = -1;
		CU = null;
		Serv.removeObserver(this);
	}
	
	public void StatusUpdate(String newStatus) throws RemoteException
	{
		Serv.SetStatus(UID, newStatus);
	}
	
	public String CheckStatus(String Uname) throws RemoteException
	{
		int lookid = Serv.GetUserID(Uname);
		return Serv.GetStatus(lookid);
	}
	
	public void JoinGroup(int GID) throws RemoteException
	{
		Serv.PutInGroup(GID, UID);
	}
	
	public void Post(int GID, int CID, Message M) throws RemoteException
	{
		if(Serv.CanPost(UID, GID) == true)
		{
			Serv.SendMessage(M, CID);
		}
		else
		{
			System.out.println("Not a member of this group, unable to post.");
		}
	}
	
	public String SeeMessage(int MID) throws RemoteException
	{
		return Serv.seeMessage(MID);
	}
	
	public void StartGroup(int GID, String GN) throws RemoteException
	{
		Serv.makegroup(GID, GN);
		Serv.SetAdmin(UID, GID);
		Serv.PutInGroup(GID, UID);
	}
	
	//Admin actions
	
	public String Promote(int POI, int GID) throws RemoteException
	{
		if(Serv.checkifadmin(GID, UID) == true)
		{
			Serv.SetAdmin(POI, GID);
			return("Complted");
		}
		else
		{
			System.out.println("Not an admin, action unavailable.");
			return("Not an admin, action unavailable.");
		}
	}
	
	public String Ban(int POI, int GID) throws RemoteException
	{
		if(Serv.checkifadmin(GID, UID) == true)
		{
			Serv.BanUser(POI, GID);
			return("Completed");
		}
		else
		{
			System.out.println("Not an admin, action unavailable.");
			return("Not an admin, action unavailable.");
		}
	}
	
	public String kick(int POI, int GID) throws RemoteException
	{
		if(Serv.checkifadmin(GID, UID) == true)
		{
			Serv.KickUser(POI, GID);
			return("Completed");
		}
		else
		{
			System.out.println("Not an admin, action unavailable.");
			return("Not an admin, action unavailable.");
		}
	}
	
	public String ChangeGroupName(String NN, int GID) throws RemoteException
	{
		if(Serv.checkifadmin(GID, UID) == true)
		{
			Serv.EditGroupName(NN, GID);
			return("Completed");
		}
		else
		{
			System.out.println("Not an admin, action unavailable.");
			return("Not an admin, action unavailable.");
		}
	}

}
