package org.koushik.javabrains.messenger.photos;

import javax.ws.rs.GET;
import javax.ws.rs.Path;
import javax.ws.rs.Produces;
import javax.ws.rs.core.MediaType;

@Path("/photos")
public class Photos{
	@GET
	@Produces(MediaType.TEXT_PLAIN)
	public String getMessages() {
		return "hello from the class photos";
	}

	
}
