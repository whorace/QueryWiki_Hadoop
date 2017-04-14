package code.inverted;


import java.io.BufferedInputStream;
import java.io.BufferedReader;
import java.io.File;
import java.io.FileInputStream;
import java.io.FileNotFoundException;
import java.io.FileReader;
import java.io.IOException;
import java.io.InputStreamReader;
import java.io.PrintWriter;
import java.io.Reader;
import java.util.ArrayList;
import java.util.Arrays;
import java.util.Comparator;
import java.util.HashMap;
import java.util.List;
import java.util.Map;
import java.util.Scanner;

import org.apache.hadoop.conf.Configuration;
import org.apache.hadoop.fs.FileSystem;

import util.StringIntegerList;
import util.StringIntegerList.StringIntegerArray;
 

public class filter {
	static class Point {
	    String word;
	    int wf;
	    int df;
	    public Point(String word, int wf, int df) {
	       this.word=word;
	       this.wf=wf;
	       this.df=df;
	    }
//	    public int compareTo(Point o) {
//	        return this.wf - o.wf;
//	    }
	}
	private static void readTxtFile(String filepath) throws IOException{
        try {  
           
            BufferedReader reader=new BufferedReader(new FileReader(filepath));
            PrintWriter out=new PrintWriter("/Users/uuisafresh/Documents/cluster/result"); 
            
            
            ArrayList<String> name=new ArrayList<String>();
            int num=0;
            long start=System.currentTimeMillis();
            String str;
            Point[] readpoint=new Point[4000000];
            int index=0;
            while((str = reader.readLine())!=null){   
            	String[] line=str.split("\t");
            	String word=line[0];
            	int wf=Integer.parseInt(line[1].split(" ")[0]);
            	int df=Integer.parseInt(line[1].split(" ")[1]);
            	
            	readpoint[index++]=new Point(word,wf,df);
            	
            }  
            
            Map<String,String> map=new HashMap<String,String>();
            Arrays.sort(readpoint,0,index, new Comparator<Point>(){
                
                public int compare(Point i1, Point i2){
                    return i1.wf - i2.wf;
                }
            });
            for(int i=index-1;i>index-50;i--){
            	map.put(readpoint[i].word, "");
            }
            Arrays.sort(readpoint,0,index, new Comparator<Point>(){
                
                public int compare(Point i1, Point i2){
                    return i1.df - i2.df;
                }
            });
            for(int i=index-1;i>index-50;i--){
            	map.put(readpoint[i].word, "");
            }
            
            for(String key:map.keySet())
            	out.write(key+" ");
            out.write("\n");
            reader.close();
            out.close();
            
        } catch (FileNotFoundException e) {  
            e.printStackTrace();  
        }  
    }  
     
    public static void main(String argv[]) throws IOException{
    	String filePath = "/Users/uuisafresh/Documents/cluster/part-r-00000";
    	readTxtFile(filePath);
    }
     
     
 
}




