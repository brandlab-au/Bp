package org.koushik.javabrains.messenger.rental;

import javax.ws.rs.GET;
import javax.ws.rs.Path;
import javax.ws.rs.Produces;
import javax.ws.rs.core.MediaType;

@Path("/rentals")
public class Rental {
	@GET
	@Produces(MediaType.TEXT_PLAIN)
	public String getRentals(){
		return "This is from class Rental";
	}

}
