package Sprint2;

import Sprint1.*;

import java.beans.XMLDecoder;
import java.beans.XMLEncoder;
import java.io.BufferedInputStream;
import java.io.BufferedOutputStream;
import java.io.FileInputStream;
import java.io.FileNotFoundException;
import java.io.FileOutputStream;
import java.io.Serializable;
import java.rmi.RemoteException;
import java.rmi.server.UnicastRemoteObject;
import java.util.ArrayList;

public class Server extends UnicastRemoteObject implements ServerInterface, Serializable
{
	

	protected Server() throws RemoteException
	{
		super();
		
		// TODO Auto-generated constructor stub
	}

	private static final long serialVersionUID = 1272873104040659769L;
	
	
	Database DB = new Database("DB");
	

	
	
	ArrayList<Client> observers = new ArrayList<Client>();
	

	@Override
	public int GetUserID(String name) throws RemoteException
	{
		for (User i : DB.Members) {
			if(name == i.getUsername()) {
				return i.getUserID();
			}
		}
		System.out.println("User is not present");
		return -1;
	}

	@Override
	public String GetStatus(int UserID) throws RemoteException
	{
		for (User i : DB.Members) {
			if(UserID == i.getUserID()) {
				return i.getStatus();
			}
		}
		System.out.println("User is not present");
		return null;
	}

	@Override
	public String GetUsername(int UserID) throws RemoteException
	{
		for (User i : DB.Members) {
			if(UserID == i.getUserID()) {
				return i.getUsername();
			}
		}
		System.out.println("User is not present");
		return null;
	}

	@Override
	public String GetPassword(int UserID) throws RemoteException
	{
		for (User i : DB.Members) {
			if(UserID == i.getUserID()) {
				return i.getPassword();
			}
		}
		System.out.println("User is not present");
		return null;
	}

	@Override
	public void SetUserID(String name, int NewID) throws RemoteException
	{
		for (User i : DB.Members) {
			if(name == i.getUsername()) {
				i.setUserID(NewID);
			}
		}
		//System.out.println("User is not present");
		
	}

	@Override
	public void SetStatus(int UserID, String NewStatus) throws RemoteException
	{
		for (User i : DB.Members) {
			if(UserID == i.getUserID()) {
				i.setStatus(NewStatus);
			}
		}
		//System.out.println("User is not present");

	}

	@Override
	public void SetUsername(int UserID, String NewUsername) throws RemoteException
	{
		for (User i : DB.Members) {
			if(UserID == i.getUserID()) {
				i.setUsername(NewUsername);
			}
		}
		//System.out.println("User is not present");

	}

	@Override
	public void SetPassword(int UserID, String NewPassword) throws RemoteException
	{
		for (User i : DB.Members) {
			if(UserID == i.getUserID()) {
				i.setPassword(NewPassword);
			}
		}
		//System.out.println("User is not present");

	}

	@Override
	public void SetAdmin(int UserID, int GroupID) throws RemoteException
	{
		for (Group i : DB.Groups) {
			if(GroupID == i.getGroupID())
				i.setAdminUser(UserID);
				notifyObservers();
		}

	}

	@Override
	public void EditGroupName(String newName, int GroupID) throws RemoteException
	{
		for (Group i : DB.Groups) {
			if(GroupID == i.getGroupID())
				i.editGroupName(newName);
				notifyObservers();
		}

	}

	@Override
	public void BanUser(int UserID, int GroupID) throws RemoteException
	{
		for (Group i : DB.Groups) {
			if(GroupID == i.getGroupID())
				i.banUser(UserID);
				notifyObservers();
		}

	}

	@Override
	public void KickUser(int UserID, int GroupID) throws RemoteException
	{
		for (Group i : DB.Groups) {
			if(GroupID == i.getGroupID())
				i.kickUser(UserID);
				notifyObservers();
		}

	}

	@Override
	public boolean CanPost(int UserID, int GroupID) throws RemoteException
	{
		for (Group i : DB.Groups) {
			if(GroupID == i.getGroupID())
			{
				if(i.canPost(UserID) == true)
				{
					return true;
				}else {return false;}
			}else {
					return false;
			}
	}
		return false;}

	@Override
	public void SendMessage(Message M, int ChannelID) throws RemoteException
	{
		for (Channel i : DB.getChannels()) {
			if(ChannelID == i.getChannelID()) {
				DB.insertMessage(M);
				i.postMessage(M.seeMessageID());
				notifyObservers();
			}
		}
	}
	
	public String seeMessage(int MessageID) throws RemoteException
	{
		return DB.getMessage(MessageID).seeContent();
	}

	@Override
	public void addObserver(Client o) throws RemoteException
	{
		observers.add(o);
		
	}

	@Override
	public void removeObserver(Client o) throws RemoteException
	{
		observers.remove(o);
	}
	
	public void notifyObservers() throws RemoteException
	{
		for(Client o: observers)
		{
			o.notifyFinished();
		}
		storeToDisk();
		
		
	}
	
	public void notifyTest() throws RemoteException
	{
		notifyObservers();
	}
	
	public int CheckCred(String UserN, String PassW) throws RemoteException 
	{
		
			int id = GetUserID(UserN);
			if(id == -1) {
				System.out.println("Username or password incorrect, please try again");
				return -1;
			}
			else {
				if(GetPassword(id) == PassW) {
					System.out.println("Username and Password checkout");
					return id;
				}else {
					System.out.println("Username or password incorrect, please try again");
					return -1;
				}
			}
	}
	
	public User ReturnUser(int id)
	{
		if (id == -1)
		{
			return null;
		}else {
			return DB.getUser(id);
		}
		
	}
	
	public void PutInGroup(int groupid, int uid) throws RemoteException
	{
		DB.getGroup(groupid).UserIDs.add(uid);
		notifyObservers();
	}
	
	public boolean checkifadmin(int groupid, int uid) throws RemoteException
	{
		return DB.getGroup(groupid).isAdmin(uid);
	}
	
	public void makegroup(int groupid, String groupname) throws RemoteException
	{
		Group newgroup = new Group(groupid, groupname);
		DB.insertGroup(newgroup);
		notifyObservers();
	}
	
	public void storeToDisk()
	{
		XMLEncoder encoder=null;
		try{
		encoder=new XMLEncoder(new BufferedOutputStream(new FileOutputStream("ServerInfo.xml")));
		}catch(FileNotFoundException fileNotFound){
			System.out.println("ERROR: While Creating or Opening the File dvd.xml");
		}
		encoder.writeObject(DB);
		encoder.close();
	}
	
	public static Database loadFromDisk()
	{
		XMLDecoder decoder=null;
		try {
			decoder=new XMLDecoder(new BufferedInputStream(new FileInputStream("ServerInfo.xml")));
		} catch (FileNotFoundException e) {
			System.out.println("ERROR: File dvd.xml not found");
			return null;
		}
		Database NDB =(Database)decoder.readObject();
		return NDB;
	}
	
	public boolean equals(Database that)
	{
		if(DB.Members.size() != that.Members.size())
		{
			return false;
		}
		return true;
	}
	
	public void DataAlready()
	{
		if(loadFromDisk() != null) {
			DB = loadFromDisk();
		}

	}

}
