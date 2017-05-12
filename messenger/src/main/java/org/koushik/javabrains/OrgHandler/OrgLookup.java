package org.koushik.javabrains.OrgHandler;

import java.sql.*;
import java.util.HashMap;
import com.microsoft.sqlserver.jdbc.*;   

public class OrgLookup {
	private HashMap <Integer, String> orgList;
	
	public OrgLookup() {
		orgList = new HashMap();
	}
	
	public HashMap getEmployees() {
        String connectionString =  
                "jdbc:sqlserver://192.168.100.44:1433;"  
                + "database=bp;"  
                + "user=bp;"  
                + "password=User2017;"  
                //+ "encrypt=true;"  
                //+ "trustServerCertificate=false;"  
                + "hostNameInCertificate=*.database.windows.net;"  
                + "loginTimeout=30;"; 
/*        String url = "jdbc:sqlserver://192.168.100.44:1433;databasename=bp;integratedSecurity=true";
        String driver = "com.microsoft.sqlserver.jdbc.SQLServerDriver";
        String userName = ""; 
        String password = "";*/

            // Declare the JDBC objects.  
            Connection conn = null;  
            ResultSet rs = null;
            try {  
	            conn = DriverManager.getConnection(connectionString);  
	    		Statement stmt = conn.createStatement();
	    		rs = stmt.executeQuery("select * from employee");
	
	            int size = 0;

	            while (rs.next()) {
	            	size++;
	            	System.out.println("Fetching row # " + size );
	            	System.out.println("id: " + rs.getInt(1) + " Name: " + rs.getString(2));
	            	orgList.put(new Integer (rs.getInt(1)),rs.getString(2));
	            }
            }  
            catch (Exception e) {  
                e.printStackTrace();  
            }  
            finally {  
                if (conn != null) try { conn.close(); } catch(Exception e) {}  
            }
           return orgList;
	}

    public static void main (String [] args) {
        OrgLookup lookup = new OrgLookup();
        HashMap map = lookup.getEmployees();
        if (!map.isEmpty())
        	System.out.println("Map is returned succesfully");
    }
	
	


}
