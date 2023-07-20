package Sprint1;

public class User
{
	
	//Initialize
	
	//BufferedImage profile_photo;
	int UserID;
	String Status;
	String username;
	String password;
	
	
	//Methods
	
	//Constructor
	
		/**
	 * @param userID
	 * @param status
	 * @param username
	 * @param password
	 */
	public User(int userID, String status, String username, String password)
	{
		super();
		UserID = userID;
		Status = status;
		this.username = username;
		this.password = password;
	}
	
	public User()
	{
		this(1,"ok","ok","ok");
	}
	
	
	//Getters and Setters
	
	/**
	 * @return the userID
	 */
	public int getUserID()
	{
		return UserID;
	}





	/**
	 * @param userID the userID to set
	 */
	public void setUserID(int userID)
	{
		UserID = userID;
	}


	/**
	 * @return the status
	 */
	public String getStatus()
	{
		return Status;
	}


	/**
	 * @param status the status to set
	 */
	public void setStatus(String status)
	{
		Status = status;
	}


	/**
	 * @return the username
	 */
	public String getUsername()
	{
		return username;
	}


	/**
	 * @param username the username to set
	 */
	public void setUsername(String username)
	{
		this.username = username;
	}


	/**
	 * @return the password
	 */
	public String getPassword()
	{
		return password;
	}


	/**
	 * @param password the password to set
	 */
	public void setPassword(String password)
	{
		this.password = password;
	}


	public static void main(String[] args)
	{
		// TODO Auto-generated method stub

	}

}
