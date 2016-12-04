# Setup Web App

##Prerequisites

* System running Ubuntu 16.04 LTS on a physical or virtual machine
    * The deployment scripts have been tailored and tested to work on a Ubuntu 16.04 LTS system
* [AWS Command Line Interface](http://docs.aws.amazon.com/cli/latest/userguide/cli-chap-getting-set-up.html) installed and configured with:
    * Credentials of an AWS account that has access to [AWS IAM Roles](http://docs.aws.amazon.com/IAM/latest/UserGuide/id_roles.html)
    * Default region to be **us-west-2**



### 1. Create an IAM role that has the PowerUserAccess policy set

### 2. Clone this repository: [ITMO-444-Repo](https://github.com/illinoistech-itm/tgidwani)

### 3. cd into this repository 
### 4. Run script: setup-web-app.sh
`./setup-web-app.sh "<key-name>" "<iam-profile-name>"`

- Replace **&lt;key-name&gt;** with the name of valid key to enable ssh logins into EC2 instances
- Replace **&lt;iam-profile-name&gt;** with the name of an valid IAM profile that has the PowerUserAccess policy set

****Note: Allows the web application to interact with AWS without having to hardcode credentials in the source code****

