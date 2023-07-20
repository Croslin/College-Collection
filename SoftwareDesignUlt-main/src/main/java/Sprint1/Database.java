package Sprint1;

import java.util.ArrayList;

public class Database
{
	//Lists
	
	
	public ArrayList<User> Members = new ArrayList<User>();
	
	public ArrayList<Group> Groups = new ArrayList<Group>();
	
	ArrayList<Channel> Channels = new ArrayList<Channel>();
	
	ArrayList<Message> Messages = new ArrayList<Message>();
	
	String name;
	
	
	/**
	 * @param members
	 * @param groups
	 * @param channels
	 * @param messages
	 */
	public Database(String n)
	{
		super();
		ArrayList<User> Members = new ArrayList<User>();
		
		ArrayList<Group> Groups = new ArrayList<Group>();
		
		ArrayList<Channel> Channels = new ArrayList<Channel>();
		
		ArrayList<Message> Messages = new ArrayList<Message>();
		
		name = n;
		
	}
	
	public Database()
	{
		this("DB");
	}

	//Initializations
	
	
	
	//Methods
	
	//Gets from lists
	public User getUser(int Userid) {
		for (User i : this.Members) {
			if(Userid == i.UserID) {
				return i;
			}
		}
		System.out.println("userid is not present");
		return null;
	}
	
	public Group getGroup(int Groupid) {
		for (Group i : Groups) {
			if(Groupid == i.GroupID) {
				return i;
			}
		}
		System.out.println("groupid is not present");
		return null;
	}
	
	public Channel getChannel(int Channelid) {
		for (Channel i : getChannels()) {
			if(Channelid == i.Channel_ID) {
				return i;
			}
		}
		System.out.println("channelid is not present");
		return null;
	}
	
	public Message getMessage(int Messageid) {
		for (Message i : Messages) {
			if(Messageid == i.MessageID) {
				return i;
			}
		}
		System.out.println("messageid is not present");
		return null;
	}
	
	//Inserts into lists
	
	public void insertUser(User u) {
		this.Members.add(u);
	}
	
	public void insertGroup(Group g) {
		this.Groups.add(g);
	}
	public void insertMessage(Message m) {
		this.Messages.add(m);
	}
	
	public void insertChannel(Channel c) {
		this.getChannels().add(c);
	}
	
	//end
	
	
	
	public static void main(String[] args)
	{
		// TODO Auto-generated method stub
		
		//User Albert;
		
		//User p;
		
		//Database testing = new Database("testing");
		//System.out.println(testing.name);
		//Albert = new User(0, "Splendid", "AlbertW", "AW1");
		//testing.insertUser(Albert);
		
		//p = testing.getUser(0);
		//System.out.println(p.username);
		
		

	}



	public ArrayList<Channel> getChannels()
	{
		return Channels;
	}

}
