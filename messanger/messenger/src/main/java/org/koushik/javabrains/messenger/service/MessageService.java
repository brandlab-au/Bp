package org.koushik.javabrains.messenger.service;

import java.util.ArrayList;
import java.util.Date;
import java.util.List;
import org.koushik.javabrains.messenger.model.Model;

public class MessageService {
	
	

	public List<Model> getAllModels(){
	
		Model m1 = new Model(1,"hello here we go","jonathon");
		Model m2 = new Model(2,"nice to know","Francisco");
		List<Model> list = new ArrayList<>();
		list.add(m1);
		list.add(m2);
		return list;
	}
	
}
