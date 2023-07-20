var w = 1000;
var h = 500;

var Mleft = 50;
var Mright = 50;
var Mbottom = 50;
var Mtop = 10;
    
     var Dwidth = w - Mleft - Mright;

    var Dheight = h - Mtop - Mbottom;
    
    
    var xScale = d3.scaleLinear()
                .domain([30,48])
                .range([0,w])
    
     var yScale = d3.scaleLinear()
                .domain([-10,100])
                .range([h,0])
     
    var xAxis = d3.axisBottom(xScale)

    var yAxis = d3.axisLeft(yScale)   
    
    
var createGraph = function(Data)
{

    //Creates the svg
    d3.select("body")
    .append("svg")
    .attr("height",h)
    .attr("width",w)

    
    //creates a section of the svg for happiness
    d3.select("svg")
    .append("g")
    .attr("id","Happiness_graph")
    .attr("transform","translate("+Mleft+","+Mtop+")");
    
    //creates a section of the svg for Sad
    d3.select("svg")
    .append("g")
    .attr("id","Bad_graph")
    .attr("transform","translate("+Mleft+","+Mtop+")");
    
     //creates a section of the svg for Life
    d3.select("svg")
    .append("g")
    .attr("id","Life_graph")
    .attr("transform","translate("+Mleft+","+Mtop+")");
    
     //creates a section of the svg for Nice
    d3.select("svg")
    .append("g")
    .attr("id","Nice_graph")
    .attr("transform","translate("+Mleft+","+Mtop+")");
     
    //creates a g for axis
    d3.select("svg")
    .append("g")
    .classed("axis", true);
    
    //creates a x axis
    d3.select(".axis")
   .append("g")
   .attr("id", "xAxis")
   .attr("transform","translate("+Mleft+","+(Mtop+Dheight)+")")
   .call(xAxis)
    
    
    //creates a y axis
    d3.select(".axis")
   .append("g")
   .attr("id","yAxis")
   .attr("transform","translate(25,"+Mtop+")")
   .call(yAxis)
    
    //Adds Circles for happiness graph
    d3.select("#Happiness_graph")
    .selectAll("circle")
    .data(Data)
    .enter()
    .append("circle")
     .attr("cx",function(Hours)
          {return xScale(Hours.working_Hours)})
    .attr("cy",function(Happy)
         {return (yScale(Happy.Happiness_Score*10))})
    .attr("r",4) 
    .attr("fill","green")
    
    //Adds Circles for suicides
     d3.select("#Bad_graph")
    .selectAll("circle")
    .data(Data)
    .enter()
    .append("circle")
    .attr("cx",function(Hours)
          {return xScale(Hours.working_Hours)})
    .attr("cy",function(Bad)
         {return (yScale(Bad.Suicides_Per_1000))})
    .attr("r",4) 
    .attr("fill","red")
    
    //Adds Circles for Life
     d3.select("#Life_graph")
    .selectAll("circle")
    .data(Data)
    .enter()
    .append("circle")
    .attr("cx",function(Hours)
          {return xScale(Hours.working_Hours)})
    .attr("cy",function(Life)
         {return (yScale(Life.Life_Expectancy))})
    .attr("r",4) 
    .attr("fill","pink")
    
    //Adds Circles for Nice
     d3.select("#Nice_graph")
    .selectAll("circle")
    .data(Data)
    .enter()
    .append("circle")
    .attr("cx",function(Hours)
          {return xScale(Hours.working_Hours)})
    .attr("cy",function(Nice)
         {return (yScale(Nice.Generosity*100))})
    .attr("r",4) 
    .attr("fill","blue")
    
    ButtonWorkings()
    
}
var ButtonWorkings = function(Data)
{
        //on click for button happiness off
    d3.select("#HappinessOff")
    .on("click",function(Data){
        d3.select("#Happiness_graph")
        .selectAll("circle")
        .attr("r",0) 
    })
         //on click for button happiness 
    d3.select("#Happiness")
    .on("click",function(Data){
        d3.select("#Happiness_graph")
        .selectAll("circle")
        .attr("r",4) 
    })
        //on click for button Suicides off
    d3.select("#SuicidesOff")
    .on("click",function(Data){
        d3.select("#Bad_graph")
        .selectAll("circle")
        .attr("r",0) 
    })
         //on click for button Suicides
    d3.select("#Suicides")
    .on("click",function(Data){
        d3.select("#Bad_graph")
        .selectAll("circle")
        .attr("r",4) 
    })
     //on click for button Life Off
    d3.select("#LifeExpectancyOff")
    .on("click",function(Data){
        d3.select("#Life_graph")
        .selectAll("circle")
        .attr("r",0) 
    })
     //on click for button Life
    d3.select("#LifeExpectancy")
    .on("click",function(Data){
        d3.select("#Life_graph")
        .selectAll("circle")
        .attr("r",4) 
    })
     //on click for button Nice
    d3.select("#GenerosityOff")
    .on("click",function(Data){
        d3.select("#Nice_graph")
        .selectAll("circle")
        .attr("r",0) 
    })    
     //on click for button Nice
    d3.select("#Generosity")
    .on("click",function(Data){
        d3.select("#Nice_graph")
        .selectAll("circle")
        .attr("r",4) 
    })    
     //on click for button All
    d3.select("#All")
    .on("click",function(Data){
        d3.select("svg")
        .selectAll("circle")
        .attr("r",4) 
    })    

    
    
    
}





var DataPromise = d3.csv("CompiledData.csv")
DataPromise.then(
function(Data)
    {
        
        createGraph(Data);
    },
    function()
    {
        console.log("Not Finding Data")
    }
)