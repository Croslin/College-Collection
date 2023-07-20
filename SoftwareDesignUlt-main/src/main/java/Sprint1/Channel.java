package Sprint1;

import java.util.ArrayList;

public class Channel
{
	
	
	//Initialization
	
	int Channel_ID;
	
	String Topic;
	
	
	//list
	
	ArrayList<Integer> MessageIDs = new ArrayList<Integer>();
	
	
	//Methods
	
	
	
	public void deleteMessage(int messageID) {
		for (int i = 0; i < MessageIDs.size(); i++) {
			if(messageID == MessageIDs.get(i)) {
				MessageIDs.remove(i);
			}
		}
	}
	
	public void postMessage(int MID) {
		MessageIDs.add(MID);
	}

	/**
	 * @param channel_ID
	 * @param topic
	 * @param messageIDs
	 */
	public Channel(int channel_ID, String topic)
	{
		super();
		Channel_ID = channel_ID;
		Topic = topic;
		//MessageIDs = messageIDs;
		ArrayList<Integer> MessageIDs = new ArrayList<Integer>();
	}
	
	public Channel()
	{
		this(1,"ok");
	}

	public static void main(String[] args)
	{
		// TODO Auto-generated method stub

	}
	
	public int getChannelID() {
		return Channel_ID;
	}

}
