package Sprint1;

import java.util.ArrayList;

public class Group
{
	
	//Initialization
	
	int GroupID;
	
	String GroupName;
	
	
	//Lists
	//Integer is userID
	public ArrayList<Integer> UserIDs = new ArrayList<Integer>();
	
	//Integer is channelID
	ArrayList<Integer> Channels = new ArrayList<Integer>();
	
	//Integer is userID
	ArrayList<Integer> BannedUsers = new ArrayList<Integer>();
	
	//Integer is userID
	public ArrayList<Integer> Admins = new ArrayList<Integer>();
	
	//Getters  
	
	public int getGroupID() {
		return GroupID;
	}
	public String getGroupName() {
		return GroupName;
	}
	

	
	
	//Methods
	
	public void setAdminUser(int userID) {
		Admins.add(userID);
	}
	
	public void editGroupName(String newName) {
		GroupName = newName;
	}
	
	public void banUser(int userID) {
		BannedUsers.add(userID);
		for (int i = 0; i < UserIDs.size(); i++) {
			if(userID == UserIDs.get(i)) {
				UserIDs.remove(i);
			}
		}
	}
	
	public void kickUser(int userID) {
		for (int i = 0; i < UserIDs.size(); i++) {
			if(userID == UserIDs.get(i)) {
				UserIDs.remove(i);
			}
		}
	}
	
	public boolean isAdmin(int userID) {
		for(int i = 0; i <  Admins.size(); i++) {
			if(userID == Admins.get(i)) {
				return true;
			}
		}
		return false;
	}
	
	//Don't know what this does
	//maybe it goes through banned users or userids although it should return something if that is the case
	public boolean canPost(int userID){
		for(int i : UserIDs) {
			if(userID == UserIDs.get(i)) {
				return true;
			}
		}
		return false;
	}
	
	//this should be in channel
	//public void sendMessage(int channelID, Message m) {
		
	//}
	
	
	
	/**
	 * @param groupID
	 * @param groupName
	 * @param userIDs
	 * @param channels
	 * @param bannedUsers
	 * @param admins
	 */
	public Group(int groupID, String groupName)
	{
		super();
		GroupID = groupID;
		GroupName = groupName;
		//UserIDs = userIDs;
		//Channels = channels;
		//BannedUsers = bannedUsers;
		//Admins = admins;
	}
	
	public Group()
	{
		this(1,"ok");
	}

	public static void main(String[] args)
	{
		// TODO Auto-generated method stub

	}

}
