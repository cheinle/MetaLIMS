
# WARNING: 
### NanoLIMS is currently under major re-construction. Suggestion by developers is to not pull current version at this time. A formal release will be created when changes have been finalized. Thank you for your understanding. Please email myself if you have any questions (cheinle@ntu.edu.sg) Apologies for any inconvenience. CHEINLE - Oct 15, 2016 


# NanoLIMS, A Simple Open-Source LIMS System for Small Metagenomic Labs

## NanoLIMS v2 Installation and Usage
### Installation
*    [NanoLIMS v2.0 Hosted Installation (via AWS Lightsail)](https://github.com/cheinle/NanoLIMS/wiki/NanoLIMS--v2.0-Hosted-Installation-(via-AWS-Lightsail))
*    [NanoLIMS v2.0 Full Manual Installation](https://github.com/cheinle/NanoLIMS/wiki/NanoLIMS--v2.0-Full-Manual-Installation)

### Manual
*    [NanoLIMS v2.0 Manual For Admin](https://github.com/cheinle/NanoLIMS/wiki/NanoLIMS-v2.0-Manual-For-Admin)
*    [NanoLIMS v2.0 Manual For Basic Usage](https://github.com/cheinle/NanoLIMS/wiki/NanoLIMS-v2.0-Manual-For-Basic-Usage)
*    [NanoLIMS v2.0 Manual For Developers](https://github.com/cheinle/NanoLIMS/wiki/NanoLIMS-v2.0-Manual-For-Developers)

### Misc   
*    [nanolims.sh](https://github.com/cheinle/NanoLIMS/wiki/nanolims.sh)
*    [lamp.sh](https://github.com/cheinle/NanoLIMS/wiki/lamp.sh)


##Synopsis

Laboratory Information Management Systems (LIMS) have grown and changed over the years from simple reporting tools to more elaborate packages including instrument integration, analysis, and complex workflows. At its most basic, LIMS allows users to easily record and access various sample metadata. As the cost of sequencing continues to fall, smaller groups increasingly initiate and manage larger sequencing projects, and must take on the complexity of data storage for high volumes of samples. This has created a need for low cost LIMS that contain flexible input fields to accommodate the unique nature of individual labs. Many labs do not have a dedicated IT position, so LIMS must also be easy to setup and maintain with minimal technical proficiency. 
 
NanoLIMS is a free and open source web-based application that is available for deployment and customization via GitHub at no cost. NanoLIMS focus is to store sample collection and processing metadata prior to sequencing and analysis pipelines. NanoLIMS was built with the intended use by environmental metagenomics labs and in addition to storing generic sample collection information and DNA/RNA processing information, the user can also add fields specific to the users lab. NanoLIMS can also produce a basic sequencing submission form compatible with the proprietary Clarity LIMS system used by some sequencing facilities. NanoLIMS suggests use of Amazon web service (AWS) Lightsail web-hosting combined with easy to use bash scripts for ease of deployment.

NanoLIMS overcomes key challenges common in LIMS by giving labs access to a low-cost and open source tool that also has the flexibility to meet individual lab needs by allowing custom recording fields. NanoLIMS manages simple installation through use of easy to run bash scripts combined with AWS Lightsail web-hosting for simple deployment. By making the web application open source and hosting it on GitHub, we hope to encourage the community to build upon NanoLIMS, making it more robust and tailored to the needs of more researchers. 

##Authors/Contact Info

Cassie Heinle

cheinle@ntu.edu.sg

Singapore Center For Environmental Life Sciences and Engineering

Nanyang Technological University

www.scelse.sg

Email bugs and feature requests to: cheinle@ntu.edu.sg


##License
[Read LICENSE] (https://github.com/cheinle/NanoLIMS/blob/master/license.md)



