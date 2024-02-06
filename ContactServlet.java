

import java.io.IOException;
import java.io.PrintWriter;
import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.PreparedStatement;
import java.sql.SQLException;

import javax.servlet.ServletException;
import javax.servlet.annotation.WebServlet;
import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;

@WebServlet("/contact")
public class ContactServlet extends HttpServlet{
	
	//create the query
	private static final String INSERT_QUERY ="INSERT INTO USER(FNAME,LNAME,EMAIL,WORK,MOBILE,CITY) VALUES(?,?,?,?,?,?)";
	@Override
	protected void doGet(HttpServletRequest req, HttpServletResponse res) throws ServletException, IOException {
		//get PrintWritter
		PrintWriter pw = res.getWriter();
		//set content type
		res.setContentType("text/html");
		//read the form values
		String fname = req.getParameter("fname"); 
		String lname = req.getParameter("lname"); 
		String email = req.getParameter("email"); 
		String work = req.getParameter("work"); 
		String mobile = req.getParameter("mobile"); 
		String city = req.getParameter("city"); 
		
		System.out.println("Name: "+fname);
		System.out.println("Mobile: "+lname);
		System.out.println("Email: "+email);
		System.out.println("Work: "+work);
		System.out.println("Work: "+work);
		System.out.println("Work: "+city);
		
		
		//load the jdbc driver
		try {
			Class.forName("com.mysql.jdbc.Driver");
		} catch (ClassNotFoundException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
		//create the connection
		try(Connection con = DriverManager.getConnection("jdbc:mysql://localhost:3306/db1","root","admin");
				PreparedStatement ps= con.prepareStatement(INSERT_QUERY); ){
			//SET THE VALUE
			ps.setString(1, fname);
			ps.setString(2, lname);
			ps.setString(3, email);
			ps.setString(4, work);
			ps.setString(5, mobile);
			ps.setString(6, city);
			
			//execute the query
			int count=ps.executeUpdate();
			if(count==0){
				pw.println("Record not stored into database");
			}else {
				pw.println("Record stored into database");
			}
	  	}catch(SQLException se) {
			pw.println(se.getMessage());
			se.printStackTrace();
		}catch(Exception e) {
			pw.println(e.getMessage());
			e.printStackTrace();
		}
		
		
		//close the stream
		pw.close();
	}
	@Override
	protected void doPost(HttpServletRequest req, HttpServletResponse resp) throws ServletException, IOException {
		// TODO Auto-generated method stub
		doGet(req, resp);
	}

}


//<input type="text" name="name">