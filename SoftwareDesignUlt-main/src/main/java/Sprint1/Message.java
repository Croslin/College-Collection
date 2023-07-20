package Sprint1;

import java.time.LocalDateTime;
import java.time.format.DateTimeFormatter;

public class Message
{
	
	
	//items
	
	String content;
	
	int MessageID;
	
	//LocalDateTime TimeStamp;
	
	String TimeStamp;
	
	int PosterID;
	
	

	/**
	 * @param content
	 * @param messageID
	 * @param timeStamp
	 */
	public Message(String content, int messageID, int PosterID)
	{
		super();
		this.content = content;    
		LocalDateTime now = LocalDateTime.now();   
		DateTimeFormatter format = DateTimeFormatter.ofPattern("dd-MM-yyyy HH:mm:ss");  
		String formatDateTime = now.format(format);     
		MessageID = messageID;
		TimeStamp = formatDateTime;
	}
	
	public Message()
	{
		this("ok",1,1);
	}
	
	public String seeContent()
	{
		return this.content;
	}
	public int seeMessageID()
	{
		return this.MessageID;
	}



	public static void main(String[] args)
	{
		// TODO Auto-generated method stub

	}

}
