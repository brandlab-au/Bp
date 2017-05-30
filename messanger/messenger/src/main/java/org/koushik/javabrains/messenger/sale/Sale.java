package org.koushik.javabrains.messenger.sale;

import java.util.List;

import javax.ws.rs.GET;
import javax.ws.rs.Path;
import javax.ws.rs.Produces;
import javax.ws.rs.core.MediaType;

import org.koushik.javabrains.messenger.model.Model;
import org.koushik.javabrains.messenger.service.MessageService;



@Path("/sales")
public class Sale {
	
	MessageService m = new MessageService();
	@GET
	@Produces(MediaType.APPLICATION_XML)
	public List<Model> getSales(){
		return m.getAllModels();
		
	}

}
