package Sprint2;

import Sprint1.*;

import java.rmi.Remote;
import java.rmi.RemoteException;

public interface ServerInterface extends Remote
{
	
	//Getters
	
	public int GetUserID(String name) throws RemoteException;
	
	public String GetStatus(int UserID) throws RemoteException;
	
	public String GetUsername(int UserID) throws RemoteException;
	
	public String GetPassword(int UserID) throws RemoteException;
	
	
	//Setters
	
	public void SetUserID(String name, int NewID) throws RemoteException;
	
	public void SetStatus(int UserID, String NewStatus) throws RemoteException;
	
	public void SetUsername(int UserID, String NewUsername) throws RemoteException;
	
	public void SetPassword(int UserID, String NewPassword) throws RemoteException;
	
	public void SetAdmin(int UserID, int GroupID) throws RemoteException;
	
	
	//Methods
	
	public void EditGroupName(String newName, int GroupID) throws RemoteException;
	
	public void BanUser(int UserID, int GroupID) throws RemoteException;
	
	public void KickUser(int UserID, int GroupID) throws RemoteException;
	
	public boolean CanPost(int UserID, int GroupID) throws RemoteException;
	
	public void SendMessage(Message M, int ChannelID) throws RemoteException;

	public void notifyTest() throws RemoteException;

	public int CheckCred(String userN, String passW) throws RemoteException;

	public User ReturnUser(int uID) throws RemoteException;
	
	public void PutInGroup(int groupid, int uid) throws RemoteException;
	
	public boolean checkifadmin(int groupid, int uid) throws RemoteException;

	public String seeMessage(int MessageID) throws RemoteException;
	
	public void makegroup(int groupid, String groupname) throws RemoteException;
	
	//Observer
	
	public void addObserver(Client o) throws RemoteException;
	public void removeObserver(Client o) throws RemoteException;
	
	//public void notifyFinished() throws RemoteException;
	
	

}
